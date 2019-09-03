<?php

namespace App\Http\Controllers;

use App\Job;
use App\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{


    public function rules()
    {
        return array(
            'job_id' => 'required|integer'
        );
    }

    public function store(Request $request)
    {
        $validator = validator($request->all(), $this->rules());
        $errors = $validator->errors()->toArray();
        if (isset($errors) && $errors) {
            return response(['error' => 1, 'errors' => $errors], 422);
        }
        $user = $request->user();
        /* @var Like $like */
        $like = Like::query()->where('job_id', $request->job_id)->where('user_id', $user->id)->first();
        if ($like) {
            $like->delete();
        } else {
            $like = new Like();
            $like->user_id = $user->id;
            $like->job_id = $request->job_id;
            $like->status = Like::LIKED_STATUS;
            $like->save();
        }
        /* @var Job $job*/
        $job = Job::find($request->job_id);
//        var_dump($job->like)
        $likes = count($job->likes);
        return response(['error' => 0, 'likes' => $likes], 200);
    }
}
