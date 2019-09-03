<?php

namespace App\Http\Controllers;

use App\Job;
use App\Services\CrmService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\User;

class ProfileController extends Controller
{

    public function updateRules(User $user)
    {

        $currentYear = Carbon::now()->year;
        $hundredYearsAgo = (new Carbon("100 years ago"))->year;
        return array(
            'first_name' => 'required|string',
            'second_name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'newsletter' => 'in:true,false',
            'gender' => 'required|string|in:' . implode(USER::GENDERS, ','),
            'year' => 'required|integer|between:' . $hundredYearsAgo . ',' . $currentYear,
            'date_of_birth' => 'required|date',
        );
    }

    public function updateValidationMessages()
    {
        return array(
            'first_name.required' => __('app.first_name_required'),
            'first_name.string' => __('app.first_name_string'),
            'second_name.required' => __('app.second_name_required'),
            'second_name.string' => __('app.second_name_string'),
            'email.required' => __('app.email_required'),
            'email.unique' => __('app.email_unique'),
            'email.email' => __('app.email_email'),
            'newsletter.boolean' => __('app.newsletter_boolean'),
            'gender.required' => __('app.gender_required'),
            'gender.string' => __('app.gender_string'),
            'gender.in' => __('app.gender_in'),
            'year.required' => __('app.year_required'),
            'year.integer' => __('app.year_integer'),
            'year.between' => __('app.year_between'),
            'date_of_birth.required' => __('app.date_of_birth_required'),
            'date_of_birth.date' => __('app.date_of_birth_date'),
        );
    }

    public function index(Request $request)
    {
        /* @var $user User */
        $user = $request->user();
        /* @var $job Job */
        $job = $user->jobs()->get();
        return view('profile')->with('job', $job->toArray())->with('profile', $user->toArray());
    }

    public function update(Request $request)
    {
        /*
        * @var $user User
        * @var $oldUser User
        */
        $user = $oldUser = $request->user();
        $data = $request->all();
        $data['date_of_birth'] = $data['day'] . '-' . $data['month'] . '-' . $data['year'];
        $validator = validator($data, $this->updateRules($user), $this->updateValidationMessages());
        $errors = $validator->errors()->toArray();
        if (isset($errors) && $errors) {
            return response(['error' => 1, 'errors' => $errors], 422);
        }
        try {
            $user->first_name = $request->first_name;
            $user->second_name = $request->second_name;
            $user->email = $request->email;
            $user->newsletter = $request->newsletter;
            $user->gender = $request->gender;
            $user->birth_date = $request->year . '-' . $request->month . '-' . $request->day;
            $user->save();
            if (ENV('DIRECT_CRM')) {
                (new CrmService())->updateUser($user, $oldUser);
            }
            return response(['error' => 0, 'message' => __('app.success')], 200);
        } catch (\Exception $e) {
            try {
                $oldUser->save();
                throw $e;
            } catch (\Exception $c) {
                return response(['error' => 1, 'errors' => array('all_errors' => [$e->getMessage()])], 422);
            }
        }
    }


}
