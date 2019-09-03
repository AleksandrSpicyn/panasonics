<?php

namespace App\Http\Controllers;

use App\Job;
use App\Like;
use App\Services\CrmService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    public function rules()
    {
        return array(
            'title' => 'required|string|max:70',
            'img' => 'image',
            'privacy' => 'required|in:true',
            'description' => 'required|string|max:255',
        );
    }

    public function updateRules()
    {
        return array(
            'id' => 'required|integer|exists:jobs,id',
            'title' => 'required|string|max:70',
            'img' => 'image',
            'description' => 'required|string|max:255',
            'created_at' => 'required|date',
            'status' => 'required|string|in:' . implode(Job::STATUSES, ','),
            'comment' => 'nullable|string|max:255|required_if:status,' . Job::JOB_REFUSE_STATUS
        );
    }

    public function updateValidationMessages()
    {
        return array(
            'id.required' => __('app.id_required'),
            'id.integer' => __('app.id_integer'),
            'id.exists' => __('app.id_exists'),
            'privacy.in' => __('app.need_privacy'),
            'title.required' => __('app.title_required'),
            'title.string' => __('app.title_string'),
            'title.max' => __('app.title_max'),
            'description.required' => __('app.description_required'),
            'description.string' => __('app.description_string'),
            'description.max' => __('app.description_max'),
            'created_at.required' => __('app.created_at_required'),
            'created_at.date' => __('app.created_at_date'),
            'status.required' => __('app.status_required'),
            'status.string' => __('app.status_string'),
            'status.in' => __('app.status_in'),
            'comment.string' => __('app.comment_string'),
            'comment.max' => __('app.comment_max'),
            'comment.required_if' => __('app.comment_required_if'),
            'img.image' => __('app.image_image'),
            'img.size' => __('app.image_size'),
        );
    }

    public function index(Request $request)
    {
        $user = array();
        $likes = array();
        $userJob = array();
        /* @var Job $jobs */
        $jobs = Job::query()->where('status', Job::JOB_ACCEPT_STATUS)->orderBy('created_at', 'desc')->paginate('8');
        if (Auth::check()) {
            /* @var User $user */
            $user = $request->user();
            $likes = $user->likes()->pluck('job_id')->toArray();
            $userJob = $user->jobs;
            if ($userJob) {
                $jobs = $jobs->where('user_id', '!=', $user->id);
            }
        }

        return view('all_jobs')->with('user', $user)->with('jobs', $jobs)->with('likes', $likes)->with('userJob', $userJob);
    }

    public function loadMore(Request $request)
    {
        $user = array();
        $likes = array();
        $userJob = array();
        if (Auth::check()) {
            /* @var User $user */
            $user = $request->user();
            $likes = $user->likes()->pluck('job_id')->toArray();
        }
        $jobs = Job::query()->where('status', Job::JOB_ACCEPT_STATUS)->orderBy('created_at', 'desc')->paginate('8');
        return view('jobs_list')->with('jobs', $jobs)->with('likes', $likes)->with('user', $user)->with('userJob', $userJob);
    }

    public function view(Request $request)
    {
        $user = array();
        $likes = array();
        $userJob = array();
        $sortOptions = array(
            'date',
            'likes'
        );

        $jobs = Job::query()->where('status', Job::JOB_ACCEPT_STATUS);
        if (isset($request->sort)) {
            $sort = $request->sort;
            if (in_array($sort, $sortOptions)) {
                switch ($sort) {
                    case 'date':
                        if ($request->on == "true") {
                            $jobs->orderBy('created_at', 'asc');
                        } else {
                            $jobs->orderBy('created_at', 'desc');
                        }
                        break;
                    case 'likes':
                        if ($request->on == "true") {
                            $jobs->withCount('likes')->orderBy('likes_count', 'desc');
                        } else {
                            $jobs->orderBy('created_at', 'desc');
                        }
                        break;
                }
            }
        }
        $countJobs = 8;
        if ($request->open_page) {
            $countJobs = $countJobs * $request->open_page;
        }
        /* @var Job $jobs */
        $jobs = $jobs->paginate($countJobs);
//        var_dump($jobs);exit;
        if (Auth::check()) {
            /* @var User $user */
            $user = $request->user();
            $likes = $user->likes()->pluck('job_id')->toArray();
            $userJob = $user->jobs;
            if ($userJob) {
                $jobs = $jobs->where('user_id', '!=', $user->id);
            }
        }

        return view('jobs_list')->with('user', $user)->with('jobs', $jobs)->with('likes', $likes)->with('userJob', $userJob);
    }

    public function store(Request $request)
    {
        $validator = validator($request->all(), $this->rules(), $this->updateValidationMessages());
        $errors = $validator->errors()->toArray();
        if (isset($errors) && $errors) {
            return response(['error' => 1, 'errors' => $errors], 422);
        }
        if (!$request->user()->jobs) {
            if (ENV('DIRECT_CRM')) {
                $auth = (new CrmService())->upload($request);
            } else {
                $auth = true;
            }
            if (!$auth["error"]) {
                try {
                    $path = $request->file('img')->store('public/jobs');
                    $job = new Job();
                    $job->title = $request->title;
                    $job->description = $request->description;
                    $job->user_id = $request->user()->id;
                    $job->status = Job::JOB_WAIT_STATUS;
                    $job->image = $path;
                    $job->save();
                    return response(['error' => 0, 'message' => __('app.job_success')], 200);
                } catch (\Exception $e) {
                    return response(['error' => 1, 'errors' => array('all_errors' => [__('app.cant_save')])], 422);
                }
            } else {
                return response(['error' => 1, 'errors' => array('all_errors' => [__('app.cant_save')])], 422);
            }
        } else {
            return response(['error' => 1, 'errors' => array('all_errors' => [__('app.allready_have_job')])], 422);
        }
    }

    public function get(Request $request, int $id)
    {

        $job = Job::query()->where('id', $id)->first();
        $job->user = User::query()->where('id', $job->user_id)->first();
        $likes = $job->likes()->count();
        $likeUsers = $job->likes()->with('user')->get()->toArray();
        foreach ($likeUsers AS $key => $like) {
            $likeUsers[$key]['user'] = User::query()->where('id', $like["user_id"])->first()->toArray();
        }
        $job = $job->toArray();
        return view('job')->with('job', $job)->with('likes', $likes)->with('likeUsers', $likeUsers);
    }

    public function update(Request $request)
    {
        /* @var User $user */
        $user = $request->user();
        if ($request->type == 'admin' && $user->hasRole('admin')) {
            $validator = validator($request->all(), $this->updateRules(), $this->updateValidationMessages());
        } else {
            $validator = validator($request->all(), $this->rules(), $this->updateValidationMessages());
        }
        $errors = $validator->errors()->toArray();
        $imageUrl = '';
        if (isset($errors) && $errors) {
            return response(['error' => 1, 'errors' => $errors], 422);
        }
        try {
            if ($user->hasRole('admin') && $request->id) {
                /* @var Job $job */
                $job = Job::find($request->id);
            } else {
                $userJob = $user->jobs;
                $job = Job::find($userJob->id);
            }
            $job->title = $request->title;
            $job->description = $request->description;
            if ($request->type == 'admin') {
                $job->status = $request->status;
                $job->created_at = date('Y-m-d H:i:s', strtotime($request->created_at));
                $job->comment = $request->comment;
            } else {
                $job->status = Job::JOB_WAIT_STATUS;
                $job->comment = '';
            }
            if ($request->file('img')) {
                Storage::delete($job->image);
                $path = $request->file('img')->store('public/jobs');
                $job->image = $path;
                $imageUrl = Storage::url($path);
            }
            $job->save();
            return response(['error' => 0, 'message' => __('app.success'), 'image' => $imageUrl], 200);
        } catch (\Exception $e) {
            return response(['error' => 1, 'errors' => array('all_errors' => [__('app.cant_save')])], 422);
        }
    }
}
