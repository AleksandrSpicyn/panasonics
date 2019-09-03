@extends('app')
@section('content')
    <div id="app">
        @component('header')
        @endcomponent
        <section id="top">
            <div class="container">
                <div class="row">
                    <div class="col text-center text-sm-right p-0 m-0">
                        <img class="d-logo" src="assets/img/d-logo-ru.png" alt="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 col-xs-12 col-md-7 col-lg-7 col-xl-7">
                        <h1 id="event-title"
                            class="text-center text-sm-left mt-3 text-uppercase main-color font-weight-bold">{{__('app.job')}}</h1>
                        <div class="desc">
                            <p class="text-center text-sm-left text-uppercase font-weight-200 mb-0">{{__('app.power_up')}}
                                - </p>
                            <p class="text-center text-sm-left text-uppercase font-weight-400">{{__('app.show_talants')}}</p>
                        </div>
                        <div id="event-date">
                            <p class="text-center text-sm-left  font-weight-100">
                                2 {{__('app.september')}} - 29 {{__('app.december')}}
                            </p>
                        </div>
                    </div>
                </div>
                <div id="top-second" class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <h3 class="text-center text-sm-left  text-uppercase font-weight-400">
                            {{__('app.power_up')}} – {{__('app.show_talants')}}

                        </h3>
                        <p class="text-center text-sm-left sub-desc font-weight-bold">
                            {{__('app.with_serial')}}<span
                                    class="font-italic">Cirque du Soleil®</span>

                        </p>
                        <p class="text-center text-sm-left more-desc font-weight-200">
                            {{__('app.show_text')}}
                        </p>
                        <p class="full text-center text-sm-left text-lowercase font-weight-500">
                            <span>
                                <img src="assets/img/download.png" alt="">
                            </span>
                            <a data-toggle="modal"
                               data-target="#RulesModal" class="two-span">{{__('app.all_rules')}}</a>
                        </p>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                        <div id="countdown-container" class="ml-md-4">
                            <h4 class="black text-uppercase font-weight-400 text-center">{{__('app.end_event')}}
                            </h4>
                            <div id="countdown" class="text-center"></div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="top-third">
            <div class="container">
                <div class="row">
                </div>
            </div>
        </section>
        <section id="event">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h2 class="text-center text-uppercase main-color font-weight-400">
                            {{__('app.run_job_qa')}}
                        </h2>
                    </div>
                </div>
                <div class="row energy-blocks">
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 energy text-center">
                        <img src="assets/img/four_energy.png" alt="">
                        <p class="text-center font-weight-300">{{__('app.buy_any')}}
                            <span class="font-italic">Cirque du Soleil®</span></p>
                    </div>
                    <div class="col-12 col-sm-12 col-md-7 col-lg-7 col-xl-7 offset-0 offset-sm-0 offset-md-1 offset-lg-1 offset-xl-1  text-center pl-5">
                        <img id="girl" src="assets/img/photo.jpg" alt="">
                        <p class="text-center font-weight-300">
                            {{__('app.photo_talant')}}
                            <span class="font-italic">Cirque du Soleil®</span> {{__('app.upload_talant')}}
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-center">
                        @if (!Auth::check())
                            <a href="#" class="orange-event text-uppercase main-color-bg font-weight-500"
                               data-toggle="modal"
                               data-target="#AuthModal">{{__('app.run_job')}}</a>
                        @else
                            @if(!$user->jobs)
                                <a href="#" class="orange-event text-uppercase main-color-bg font-weight-500"
                                   data-toggle="modal"
                                   data-target="#JobModal">{{__('app.run_job')}}</a>
                            @else
                                <a class="orange-event text-uppercase main-color-bg font-weight-500" data-toggle="modal"
                                   data-target="#JobModal"
                                >{{__('app.allready_have_job')}}</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </section>
        <section id="prize">
            <div class="container">
                <div class="row">
                    <h2 class="text-center text-uppercase font-weight-400 main-color">{{__('app.win_tickets')}}
                        <span class="font-weight-bold font-italic text-normal">Cirque du Soleil®</span>
                        {{__('app.in_mosсow')}}!</h2>
                </div>
                <div class="row other-row  align-items-center">
                    <div class="col">
                        <p class="text-center text-sm-left text-uppercase font-weight-400 other">{{__('app.other_prizes')}}
                        </p>
                    </div>
                    <div class="col-12 col-sm-6 text-right">
                        <img src="assets/img/energy.png" alt="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-center magic">
                        <img src="assets/img/bringmagicalive.png" alt="">
                    </div>
                </div>
                <div id="prize_two">
                    <div class="row">
                        <div class="col">
                            <h3 class="text-center text-uppercase font-weight-300">{{__('app.open_serial')}},
                                <br>
                                {{__('app.with_parther')}} <span
                                        class="font-weight-bold font-italic text-normal">Cirque du Soleil®</span>
                            </h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col text-center ">
                            <img src="assets/img/lines.png" alt="">
                            <p class="text-center font-weight-300 white copyright"><span
                                        class="font-italic">Cirque du Soleil</span> и
                                логотип в виде солнца являются зарегистрированными торговыми марками и используются по
                                лицензии.<br>
                                Фото шоу “Crystal”: Мэтт Бёрд © 2019 Cirque du Soleil. Фото шоу “Luzia”: Мэтт Бёрд ©
                                2016 <span class="font-italic">Cirque du Soleil.</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @if($jobs->count() || $userJob)
            <section id="jobs">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <h2 class="text-center text-uppercase main-color font-weight-400">
                                {{__('app.jobs')}}
                            </h2>
                        </div>
                    </div>
                    <div class="row">
                        @component('jobs_list', ['jobs' => $jobs, 'likes' => $likes, 'userJob' => $userJob])
                        @endcomponent
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="text-center">
                                <a href="/jobs"
                                   class="orange-event text-uppercase main-color-bg font-weight-500">{{__('app.view_all_jobs')}}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </div>
    <!-- Modal -->
    @component('rules')
    @endcomponent
    @if($authData["token"] && $authData["email"])
        @component('reset_by_email', array('token' => $authData["token"], 'email' => $authData["email"]))
        @endcomponent
        @component('success_reset_final')
        @endcomponent
    @endif
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
        @component('privacy')
        @endcomponent
        @component('thanks')
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