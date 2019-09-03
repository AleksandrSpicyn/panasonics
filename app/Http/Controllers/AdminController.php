<?php

namespace App\Http\Controllers;

use App\Job;
use App\Services\CrmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AdminController extends Controller
{
    public function updateRules()
    {
        return array(
            'id' => 'required|integer|exists:jobs,id',
            'title' => 'required|string',
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
            'title.required' => __('app.title_required'),
            'title.string' => __('app.title_string'),
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

    public function index(Request $request, $status = '')
    {
        $query = Job::query();
        if ($status && in_array($status, Job::STATUSES)) {
            $query = $query->where('status', $status);
        }
        $jobs = $query->paginate(15);
        return view('jobs')->with('jobs', $jobs);
    }

    public function update(Request $request)
    {
        $validator = validator($request->all(), $this->updateRules(), $this->updateValidationMessages());
        $errors = $validator->errors()->toArray();
        $imageUrl = '';
        if (isset($errors) && $errors) {
            return response(['error' => 1, 'errors' => $errors], 422);
        }
        try {
            /* @var Job $job */
            $job = $oldJob = Job::find($request->id);
            $job->title = $request->title;
            $job->description = $request->description;
            $job->created_at = date('Y-m-d H:i:s', strtotime($request->created_at));
            $job->status = $request->status;
            $job->comment = $request->comment;
            if ($request->file('img')) {
                Storage::delete($job->image);
                $path = $request->file('img')->store('public/jobs');
                $job->image = $path;
                $imageUrl = Storage::url($path);
            }
            $job->save();
            if (($request->status == Job::JOB_REFUSE_STATUS) && ($request->status != $oldJob->status) && ENV('DIRECT_CRM')) {
                (new CrmService())->refuseJob($request);
            }
            return response(['error' => 0, 'message' => __('app.success'), 'image' => $imageUrl], 200);
        } catch (\Exception $e) {
            return response(['error' => 1, 'errors' => array('all_errors' => [__('app.cant_save')])], 422);
        }
    }

    public function rotate(Request $request)
    {
        $validator = validator($request->all(), array('job_id' => 'required', 'degree' => 'required'), $this->updateValidationMessages());
        $errors = $validator->errors()->toArray();
        if (isset($errors) && $errors) {
            return response(['error' => 1, 'errors' => $errors], 422);
        }
        $job = $oldJob = Job::find($request->job_id);
        /*@var Image $img*/
        $img = Image::make(Storage::get($job->image))->rotate($request->degree);
        Storage::put($job->image, (string) $img->encode());
        return response(['error' => 0, 'message' => __('app.success')], 200);
    }
}
