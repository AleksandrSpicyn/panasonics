<div class="modal front-modal fade" id="JobModal" tabindex="-1" role="dialog" aria-labelledby="JobModal"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <a class="close-modal" data-dismiss="modal" aria-label="Close">
                    <img src="/assets/img/cross.png" alt="">
                </a>
                <h5 class="modal-title" id="exampleModalLabel">{{__('app.run_job')}}</h5>
            </div>
            <div class="modal-body">
                <form action="" class="job">
                    <div class="form-group">
                        <label for="title">{{__('app.title')}}</label>
                        <input id="title" class="form-control" type="text" name="title"
                               value="{{$job["title"] ?? ''}}">
                        <div class="invalid-feedback">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('app.description')}}</label>
                        <textarea class="form-control" name="description" id="description" cols="30"
                                  rows="3">{{$job["description"] ?? ''}}</textarea>
                        <div class="invalid-feedback">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="uploadimage">
                            {{__('app.download_image')}}:
                        </label>
                        <img id="clip" src="/assets/img/clip.png" alt="">
                        <input class="form-control" type="file" id="uploadimage" name="img">
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
                </form>
            </div>
            <div class="modal-footer">
                <a class="modal-submit submit-job font-weight-300 text-center text-uppercase">{{__('app.download_job')}}</a>
            </div>
        </div>
    </div>
</div>