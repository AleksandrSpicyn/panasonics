<ul class="list-group">
    <li class="list-group-item">
        <a href="/admin">{{__('app.all')}}</a>
    </li>
    <li class="list-group-item">
        <a href="/admin/{{App\Job::JOB_ACCEPT_STATUS}}">{{ __('app.'.App\Job::JOB_ACCEPT_STATUS) }}</a>
    </li>
    <li class="list-group-item">
        <a href="/admin/{{App\Job::JOB_WAIT_STATUS}}">{{ __('app.'.App\Job::JOB_WAIT_STATUS) }}</a>
    </li>
    <li class="list-group-item">
        <a href="/admin/{{App\Job::JOB_REFUSE_STATUS}}">{{ __('app.'.App\Job::JOB_REFUSE_STATUS) }}</a>
    </li>
    <li class="list-group-item">
        <a href="/admin/{{App\Job::JOB_DELETE_STATUS}}">{{ __('app.'.App\Job::JOB_DELETE_STATUS) }}</a>
    </li>
</ul>