<?php

namespace App\Http\Controllers;

use App\Role;
use App\Services\CrmService;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use function Sodium\add;

class AuthController extends Controller
{
    use SendsPasswordResetEmails;

    public function rules()
    {
        return array(
            'email' => 'email',
            'password' => 'bail|required|string'
        );
    }

    public function resetRules()
    {
        return array(
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|string|min:6',
        );
    }

    public function passwordRules()
    {
        return array(
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|string|min:6',
        );
    }

    public function registerRules()
    {
        $currentYear = Carbon::now()->year;
        $hundredYearsAgo = (new Carbon("100 years ago"))->year;
        return array(
            'first_name' => 'required|string',
            'second_name' => 'required|string',
            'password' => 'required|string|min:6',
            'email' => 'required|email|unique:users,email',
            'newsletter' => 'in:true,false',
            'gender' => 'nullable|string|in:' . implode(USER::GENDERS, ','),
            'year' => 'required|integer|between:' . $hundredYearsAgo . ',' . $currentYear,
            'date_of_birth' => 'required|date',
        );
    }

    public function updateValidationMessages()
    {
        return array(
            'first_name.required' => __('app.first_name_required'),
            'first_name.string' => __('app.first_name_string'),
            'password.required' => __('app.password_required'),
            'confirm_password.required' => __('app.confirm_password_required'),
            'password.string' => __('app.password_string'),
            'confirm_password.string' => __('app.confirm_password_string'),
            'password.min' => __('app.password_min'),
            'confirm_password.min' => __('app.confirm_password_min'),
            'second_name.required' => __('app.second_name_required'),
            'second_name.string' => __('app.second_name_string'),
            'email.required' => __('app.email_required'),
            'email.email' => __('app.email_email'),
            'email.unique' => __('app.email_unique'),
            'newsletter.boolean' => __('app.newsletter_boolean'),
            'gender.string' => __('app.gender_string'),
            'gender.in' => __('app.gender_in'),
            'year.required' => __('app.year_required'),
            'year.integer' => __('app.year_integer'),
            'year.between' => __('app.year_between'),
            'date_of_birth.required' => __('app.date_of_birth_required'),
            'date_of_birth.date' => __('app.date_of_birth_date'),
            'token.required' => __('app.token_required'),
            'token.string' => __('app.token_required'),
        );
    }

    public function switchPassword(Request $request)
    {
        $validator = validator($request->all(), $this->passwordRules(), $this->updateValidationMessages());
        $errors = $validator->errors()->toArray();
        if (isset($errors) && $errors) {
            return response(['error' => 1, 'errors' => $errors], 422);
        }
        if ($request->password != $request->confirm_password) {
            return response(['error' => 1, 'errors' => array('all_errors' => [__('app.password_not_equal')])], 422);
        }
        $user = $oldUser = $request->user();
        $password = $request->password;
        try {
            $user->password = Hash::make($password);
            $user->save();
            if (ENV('DIRECT_CRM')) {
                (new CrmService())->updateUser($user, $oldUser, $password);
            }
            return response(['error' => 0, 'message' => __('app.reset_password_success')], 200);
        } catch (Exception $e) {
            return response(['error' => 1, 'errors' => array('all_errors' => [__('app.reset_password_failed')])], 422);
        }
    }

    public function reset(Request $request)
    {
        $validator = validator($request->all(), array('email' => 'required|string|email'), $this->updateValidationMessages());
        $errors = $validator->errors()->toArray();
        if (isset($errors) && $errors) {
            return response(['error' => 1, 'errors' => $errors], 422);
        }
        $email = $request->email;
        if (ENV('DIRECT_CRM')) {
            $auth = (new CrmService())->resetPassword(
                $email
            );
            try {
                if (!$auth["error"]) {
                    $user = User::query()->where('email', $request->email)->first();
                    if(!$user){
                        $auth = (new CrmService())->SearchAndGetCustomerData($request);
                        $user = new User();
                        $user->password = Hash::make($auth["ids"]["mindboxId"] . uniqid());
                        $user->email = $email;
                        $user->first_name = $auth["firstName"] ?? null;
                        $user->second_name = $auth["lastName"] ?? null;
                        $user->birth_date = $auth["birthDate"] ?? null;
                        $user->mindbox_id = $auth["ids"]["mindboxId"] ?? null;
                        $user->save();
                        $role = Role::query()->where('name', '=', 'owner')->first();
                        $user->attachRole($role);
                    }
                    $response = $this->broker()->sendResetLink($request->all());
                    if ($response == "passwords.sent") {
                        return response(['error' => 0], 200);
                    }
                }
                throw new Exception(__('app.user_not_found'));
            } catch (Exception $e) {
                return response(['error' => 1, 'errors' => array('all_errors' => [__('app.user_not_found')])], 422);
            }
        } else {
            $response = $this->broker()->sendResetLink($request->all());
            if ($response == "passwords.sent") {
                return response(['error' => 0], 200);
            }
            return response(['error' => 1, 'errors' => array('all_errors' => [__('app.user_not_found')])], 422);
        }
    }

    public function auth(Request $request)
    {
        $validator = validator($request->all(), $this->rules(), $this->updateValidationMessages());
        $errors = $validator->errors()->toArray();
        if (isset($errors) && $errors) {
            return response(['error' => 1, 'errors' => $errors], 422);
        }
        $email = $request->email;
        $password = $request->password;
        try {
            if (ENV('DIRECT_CRM')) {
                $auth = (new CrmService())->auth($request);
                if ($auth["ids"]["mindboxId"]) {
                    $user = User::query()->where('email', $email)->first();
                    if (!$user) {
                        $user = new User();
                        $user->password = Hash::make($password);
                        $user->email = $email;
                        $user->first_name = $auth["firstName"] ?? null;
                        $user->second_name = $auth["lastName"] ?? null;
                        $user->birth_date = $auth["birthDate"] ?? null;
                        $user->mindbox_id = $auth["ids"]["mindboxId"] ?? null;
                        $user->save();
                        $role = Role::query()->where('name', '=', 'owner')->first();
                        $user->attachRole($role);
                    }
                    Auth::login($user, true);
                    return response(['error' => 0, 'message' => __('app.auth_success')], 200);
                }
            } else {
                if (Auth::attempt(['email' => $email, 'password' => $password])) {
                    return response(['error' => 0, 'message' => __('app.auth_success')], 200);
                }
            }
            throw new Exception(__('app.auth_failed'));
        } catch (\Exception $e) {
            return response(['error' => 1, 'errors' => array('all_errors' => [__('app.login_or_password_error')])], 422);
        }
    }

    public function register(Request $request)
    {
        $data = $request->all();
        $data['date_of_birth'] = $data['day'] . '-' . $data['month'] . '-' . $data['year'];
        $validator = validator($data, $this->registerRules(), $this->updateValidationMessages());
        $errors = $validator->errors()->toArray();
        if (isset($errors) && $errors) {
            return response(['error' => 1, 'errors' => $errors], 422);
        }
        if (ENV('DIRECT_CRM')) {
            $auth = (new CrmService())->register(
                $request->email,
                $request->password
            );
        } else {
            $auth = true;
        }
        if (!$auth["error"] && (($auth["data"]["customer"]["ids"]["mindboxId"] && ENV('DIRECT_CRM')) || !ENV('DIRECT_CRM'))) {
            $user = User::query()->where('email', $request->email)->first();
            if (!$user) {
                $user = new User();
                $user->password = Hash::make($request->password);
                $user->first_name = $request->first_name;
                $user->second_name = $request->second_name;
                $user->email = $request->email;
                $user->newsletter = $request->newsletter;
                $user->gender = $request->gender;
                $user->birth_date = $request->year . '-' . $request->month . '-' . $request->day;
                $user->mindbox_id = $auth["data"]["customer"]["ids"]["mindboxId"] ?? null;
                $user->save();
                $role = Role::query()->where('name', '=', 'owner')->first();
                $user->attachRole($role);
                if (ENV('DIRECT_CRM')) {
                    try {
                        (new CrmService())->updateUser($user, $user);
                    } catch (Exception $e) {
                        Log::error("Sync: " . $e->getMessage());
                    }
                }
            }
            Auth::login($user, true);
            return response(['error' => 0, 'message' => __('app.register_success')], 200);
        }
        if (isset($auth["data"]["validationMessages"])) {
            $message = $auth["data"]["validationMessages"][0]["message"];
        } else {
            $message = __('app.cant_save_register');
        }
        return response(['error' => 1, 'errors' => array('all_errors' => [$message])], 422);
    }

    public function ticket(Request $request)
    {
        if (ENV('DIRECT_CRM')) {
            $validator = validator($request->all(), array(
                'ticket' => 'required|string',
                array(
                    'ticket.required' => __('ticket_required'),
                    'ticket.string' => __('ticket_string'),
                )
            ));
            $errors = $validator->errors()->toArray();
            if (isset($errors) && $errors) {
                return view('ticket')->with('errors', $errors);
            }
            try {
                $auth = (new CrmService())->checkTicket($request);
                $user = User::query()->where('email', $auth["email"])->first();
                if (!$user) {
                    $user = new User();
                    $user->password = Hash::make($auth["email"] . $auth['ids']["mindboxId"]);
                    $user->email = $auth["email"] ?? null;
                    $user->first_name = $auth["firstName"] ?? null;
                    $user->second_name = $auth["lastName"] ?? null;
                    $user->birth_date = $auth["birthDate"] ?? null;
                    $user->mindbox_id = $auth["ids"]["mindboxId"];
                    $user->save();
                    $role = Role::query()->where('name', '=', 'owner')->first();
                    $user->attachRole($role);
                }
                Auth::login($user, true);
                return redirect('/#jobs');
            } catch (\Exception $e) {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
    }

    public function resetByTicket(Request $request)
    {
        $validator = validator($request->all(), array('token' => 'string|required', 'email' => 'email|required'));
        $errors = $validator->errors()->toArray();
        if (isset($errors) && $errors) {
            return (new WelcomeController())->index($request);
        }
        $res = DB::table('password_resets')->where('email', '=', $request->email)->first();
        if ($res) {
            if (Hash::check($request->token, $res->token)) {
                $request->request->add(['token' => $request->token]);
                $request->request->add(['email' => $request->email]);
                return (new WelcomeController())->index($request);
            }
        }
        return redirect('/');
    }

    public function resetByEmail(Request $request)
    {
        $validator = validator($request->all(), $this->resetRules(), $this->updateValidationMessages());
        $errors = $validator->errors()->toArray();
        if (isset($errors) && $errors) {
            return response(['error' => 1, 'errors' => $errors], 422);
        }
        if ($request->password != $request->confirm_password) {
            return response(['error' => 1, 'errors' => array('all_errors' => [__('app.password_not_equal')])], 422);
        }
        $email = $request->email;
        $password = $request->password;
        $res = DB::table('password_resets')->where('email', '=', $email)->first();
        if (Hash::check($request->token, $res->token)) {
            try {
                /*@var User $user*/
                /*@var User $oldUser*/
                $user = $oldUser = User::query()->where('email', $email)->get()->first();
                if ($user) {
                    $user->password = Hash::make($password);
                    $user->save();
                    if (ENV('DIRECT_CRM')) {
                        (new CrmService())->updateUser($user, $oldUser, $password);
                    }
                    Auth::login($user, true);
                    DB::table('password_resets')->where('email', '=', $email)->delete();
                    return response(['error' => 0, 'message' => __('app.reset_password_success')], 200);
                }
                throw new Exception(__('app.token_expired'));
            } catch (\Exception $e) {
                return response(['error' => 1, 'errors' => array('all_errors' => [__('app.token_expired')])], 422);
            }
        } else {
            return response(['error' => 1, 'errors' => array('all_errors' => [__('app.token_expired')])], 422);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            return response(['error' => 0, 'message' => __('app.logout_success')], 200);
        } catch (Exception $e) {
            return response(['error' => 1, 'errors' => array('all_errors' => [__('app.logout_failed')])], 422);
        }
    }
}
