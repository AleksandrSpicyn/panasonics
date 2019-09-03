@if($userJob)
    @component('job_front', ['job' => $userJob, 'likes' => $likes, 'me' => true])
    @endcomponent
@endif
@foreach($jobs AS $job)
    @component('job_front', ['job' => $job, 'likes' => $likes, 'me' => false])
    @endcomponent
@endforeach