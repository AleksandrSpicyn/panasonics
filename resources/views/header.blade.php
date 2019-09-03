<section id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-sm-5 col-md-6 text-center text-sm-left">
                <div class="logo">
                    <a href="/">
                        <img src="assets/img/Panasonic_logo.png" alt="">
                    </a>
                </div>
            </div>
            <div class="col-12 col-sm-7 col-md-6 pt-2 pt-sm-1 text-center text-sm-right">
                <div class="event-logo">
                    <img src="assets/img/user1.png" class="d-inline-block pr-1" alt="">
                    @if (Auth::check())
                        <a href="#" class="d-inline-block text-uppercase font-weight-200 black text-decoration-none"
                           data-toggle="modal"
                           data-target="#ProfileModal">{{__('app.profile_in')}}</a>
                        <span class="pl-2 pr-2 text-center font-weight-200 ">|</span>
                        <a href="#" id="logout" class="d-inline-block text-uppercase font-weight-200 black text-decoration-none">{{__('app.logout')}}</a>
                    @else
                        <a href="#" class="d-inline-block text-uppercase font-weight-200 black text-decoration-none" data-toggle="modal"
                           data-target="#AuthModal">{{__('app.entry')}}</a>
                        <span class="pl-2 pr-2 text-center font-weight-200 ">|</span>
                        <a href="#" class="d-inline-block text-uppercase font-weight-200 black text-decoration-none" data-toggle="modal"
                           data-target="#RegisterModal">{{__('app.in_register')}}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>