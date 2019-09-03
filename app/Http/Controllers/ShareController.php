<?php

namespace App\Http\Controllers;

use App\Services\CrmService;
use App\Share;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShareController extends Controller
{
    public function rules()
    {
        return array(
            'job_id' => 'required|integer',
            'provider' => 'required|string|in:vk,fb,ok',
        );
    }

    public function share(Request $request)
    {
        if (Auth::check()) {
            $validator = validator($request->all(), $this->rules());
            $errors = $validator->errors()->toArray();
            if (isset($errors) && $errors) {
                return response(['error' => 1, 'errors' => $errors], 422);
            }
            /* @var User $user */
            $user = $request->user();
            $thisShared = $user->shares()->where('provider', $request->provider)->exists();
            if (!$thisShared) {
                /* @var Share $share */
                $share = new Share();
                $share->user_id = $user->id;
                $share->provider = $request->provider;
                $share->job_id = $request->job_id;
                $share->save();
                if (ENV('DIRECT_CRM')) {
                    $res = (new CrmService())->share($request);
                    if (!$res) {
                        $share->delete();
                    }
                }
            }
        }
        return response(['error' => 0, 'message' => __('app.success')], 200);
    }
}
