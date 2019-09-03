<div class="col-12 col-lg-6 mb-2 pr-1 pl-1">
    <div class="card @if($me) me-card @endif ">
        <div class="row no-gutters">
            <div class="col-12 col-sm-6 text-center">
                <img src="{{Storage::url($job["image"])}}" class="img-fluid" alt="">
                <a class="k-lightbox" data-title="{{$job["title"]}}"
                   data-footer="{{$job["user"]["first_name"]}} {{$job["user"]["second_name"]}}"
                   data-toggle="lightbox"
                   href="{{Storage::url($job["image"])}}">
                    <span class="fa-stack fa-lg">
                    <i class="fa fa-circle fa-stack-2x"></i>
                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                    </span>
                    <img src="{{Storage::url($job["image"])}}" class="img-fluid d-none" alt="">
                </a>
            </div>
            <div class="col-12 col-sm-6">
                <div class="card-block job-block">
                    <h4 class="text-center text-sm-left card-title job-title font-weight-500">
                        {{$job["title"]}}
                        @if($me)
                            <a href="#" class="d-inline-block text-uppercase font-weight-200 black text-decoration-none"
                               data-toggle="modal"
                               data-target="#JobModal"
                               titke="Редактировать"
                            >
                                <i class="fa fa-edit"></i>
                            </a>
                        @endif
                    </h4>
                    <p class="text-center text-sm-left job-likes "><span
                                class="like-count font-weight-bold">{{count($job["likes"])}}</span>
                        @if (Auth::check())
                            <i
                                    class="like-job fa fa-thumbs-o-up font-weight-400 @if(in_array($job["id"], $likes)) active @endif"
                                    aria-hidden="true" job="{{$job["id"]}}"></i>
                        @else
                            <i
                                    data-toggle="modal"
                                    data-target="#VoteModal"
                                    class="fa fa-thumbs-o-up font-weight-400 @if(in_array($job["id"], $likes)) active @endif"
                                    aria-hidden="true" job="{{$job["id"]}}"></i>
                        @endif
                    </p>
                    <div class="text-center text-sm-left author-meta">
                        <img src="/assets/img/user2.png" class="job-author" alt="">
                        <span class="author-name">{{$job["user"]["first_name"]}} {{$job["user"]["second_name"]}}</span>
                    </div>
                    @if($job["status"] == App\Job::JOB_ACCEPT_STATUS)
                        <div class="job-share">
                            <p class="text-center text-sm-left font-weight-500 black job-share-title">{{__('app.share')}}
                                :</p>
                            <ul class="text-center text-sm-left ">
                                <li>
                                <span data-job="{{$job["id"]}}"
                                      data-url="https://vk.com/share.php?title={{$job["title"]}}&url={{ENV('APP_URL')}}&image={{ENV('SITE_URL').Storage::url($job["image"])}}"
                                      data-provider="vk" class="share-button fa-stack fa-lg">
                                <i class="fa fa-vk fa-stack-1x"></i>
                                </span>
                                </li>
                                <li>
                                <span
                                        data-job="{{$job["id"]}}"
                                        data-url="https://connect.ok.ru/offer?url={{ENV('APP_URL')}}&imageUrl={{ENV('SITE_URL').Storage::url($job["image"])}}"
                                        data-provider="ok" class="share-button fa-stack fa-lg">
                                <i class="fa fa-odnoklassniki fa-stack-1x"></i>
                                </span>
                                </li>
                                <li>
                                <span data-job="{{$job["id"]}}"
                                      data-url="https://www.facebook.com/sharer.php?u={{ENV('APP_URL')}}"
                                      data-provider="fb" class="share-button fa-stack fa-lg">
                                <i class="fa fa-facebook fa-stack-1x"></i>
                                </span>
                                </li>
                            </ul>
                        </div>
                    @endif
                    @if($me)
                        <div>
                            <p class="text-center text-sm-left font-weight-500 black job-share-title">{{__('app.status')}}
                                :</p>
                            <p>{{__('app.'.$job["status"])}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>