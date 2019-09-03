<div class="modal front-modal fade" id="JobModal" tabindex="-1" role="dialog" aria-labelledby="JobModal"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <a class="close-modal" data-dismiss="modal" aria-label="Close">
                                <img src="/assets/img/cross.png" alt="">
                            </a>
                            <h5 class="modal-title" id="exampleModalLabel">{{__('app.edit_job')}}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <form action="" class="edit-job">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="status">{{__('app.status')}}:</label>
                                    <p id="job_status" class="{{$job["status"]}}">{{__('app.'.$job["status"])}}</p>
                                </div>
                                @if($job)
                                    @if($job["status"]== App\Job::JOB_REFUSE_STATUS && $job["comment"])
                                        <div class="form-group">
                                            <label for="comment">{{__('app.moderation_comment')}}:</label>
                                            <p id="comment">{{$job["comment"]}}</p>
                                        </div>
                                    @endif
                                @endif
                                <div class="form-group">
                                    <label for="title">{{__('app.title')}}:</label>
                                    <input id="title" class="form-control" type="text" name="title"
                                           value="{{$job["title"] ?? ''}}">
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">{{__('app.description')}}:</label>
                                    <textarea class="form-control" name="description" id="description" cols="30"
                                              rows="3">{{$job["description"] ?? ''}}</textarea>
                                    <div class="invalid-feedback">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="uploadimage">
                                        @if(!$job)
                                            {{__('app.download_image')}}:
                                        @else
                                            {{__('app.download_other_image')}}:
                                        @endif
                                    </label>
                                    <img id="clip" src="/assets/img/clip.png" alt="">
                                    <input type="file" id="uploadimage" class="form-control" name="img">
                                    <div class="invalid-feedback">
                                    </div>
                                </div>

                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input form-control" id="privacy" name="privacy">

                                    <label class="custom-control-label" for="privacy">
                                        {{__('app.accept_user_after')}}
                                        <a class="open-hide-modal" data-open="#PrivacyModal" data-hide="#JobModal" href="" target="_blank">{{__('app.accept_user_privacy')}}</a>
                                        {{__('app.accept_user_before')}}
                                    </label>
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    @if($job)
                                        <img src="{{Storage::url($job["image"])}}" class="img-fluid" alt="">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col pt-3">
                                <a class="modal-submit submit-edit-job font-weight-300 text-center text-uppercase">{{__('app.save')}}</a>
                                <span class="mt-2 d-block text-center font-weight-200">{{__('app.before_edit')}}</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>