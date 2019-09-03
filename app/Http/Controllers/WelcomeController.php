<?php

namespace App\Http\Controllers;

use App\Job;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        $user = array();
        $likes = array();
        $userJob = array();
        $token = $request->token ?? '';
        $email = $request->email ?? '';
        $limit = 8;
        /* @var Builder $jobs */
        $jobs = Job::query()->where('status', Job::JOB_ACCEPT_STATUS);
        if (Auth::check()) {
            /* @var User $user */
            $user = $request->user();
            $likes = $user->likes()->pluck('job_id')->toArray();
            $userJob = $user->jobs;
            if ($userJob) {
                $jobs = $jobs->where('user_id', '!=', $user->id);
                $limit = 7;
            }
        }
        $jobs = $jobs->limit($limit)->get();
        return view('welcome')
            ->with('user', $user)
            ->with('jobs', $jobs)
            ->with('likes', $likes)
            ->with('userJob', $userJob)
            ->with('authData', array('token' => $token, 'email' => $email)
            );
    }
}
