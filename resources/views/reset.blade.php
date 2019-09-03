<div class="modal fade front-modal" id="ResetModal" tabindex="-1" role="dialog" aria-labelledby="ResetModal"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content" id="reset">
            <div class="modal-header">
                <a class="close-modal" data-dismiss="modal" aria-label="Close">
                    <img src="/assets/img/cross.png" alt="">
                </a>
                <h5 class="modal-title" id="exampleModalLabel">{{__('app.reseting_password')}}</h5>
            </div>
            <div class="modal-body ">
                <form action="/reset" class="reset">
                    <div class="form-group">
                        <label for="email">{{__('app.your_email')}}</label>
                        <input id="email" class="form-control" type="text" name="email"
                               value="" placeholder="">
                        <div class="invalid-feedback">
                        </div>
                    </div>
                    <a class="reset-submit modal-submit font-weight-300 text-center text-uppercase d-block">{{__('app.reset_password')}}</a>
                    <div>
                        <input type="hidden" name="all_errors" class="form-control">
                        <div class="invalid-feedback text-center">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
