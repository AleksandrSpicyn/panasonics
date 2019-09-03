@extends('app')
@section('content')
    <div id="app">
        @component('header')
        @endcomponent
        <section id="jobs" class="all-jobs">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-4">
                        <h2 class="text-uppercase main-color font-weight-400">
                            {{__('app.all_jobs')}}
                        </h2>
                    </div>
                    <div class="col-8 text-right">
                        <a href="#" data-sort="date"
                           class="mt-2 mb-2 sort-trigger sort text-center text-uppercase font-weight-500 d-inline-block">
                            {{__('app.sort_date')}}
                        </a>
                        <a href="#" data-sort="likes"
                           class="mt-2 mb-2 sort-trigger sort text-center text-uppercase font-weight-500 d-inline-block">
                            {{__('app.sort_like')}}
                        </a>
                        <a href="/" data-sort=""
                           class="mt-2 mb-2 sort sort-orange text-center text-uppercase font-weight-500 d-inline-block">
                            {{__('app.back')}}
                        </a>
                    </div>
                </div>
                <div class="row all-jobs-row">
                    @component('jobs_list', ['jobs' => $jobs, 'likes' => $likes, 'userJob' => $userJob])
                    @endcomponent
                </div>
                <div class="row">
                    <div class="col-12 text-center">
                        <a href="/"
                           data-page="1"
                           class="mt-5 mb-2 load-more sort sort-orange text-center text-uppercase font-weight-500 d-inline-block">
                            {{__('app.load_more')}}
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Modal -->
    @if (!Auth::check())
        @component('auth')
        @endcomponent
        @component('register')
        @endcomponent
        @component('reset')
        @endcomponent
        @component('success_reset')
        @endcomponent
        @component('only_register_vote')
        @endcomponent
    @else
        @component('profile', ['user' => $user])
        @endcomponent
        @component('switch_password')
        @endcomponent
        @if(!$userJob)
            @component('job_modal', ['job' => []])
            @endcomponent
        @else
            @component('job_modal_edit', ['job'=>$userJob])
            @endcomponent
        @endif
    @endif
    <!-- END Modal -->
@endsection