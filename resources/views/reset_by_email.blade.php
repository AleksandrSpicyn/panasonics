<div class="modal fade front-modal" id="ResetByEmailModal" tabindex="-1" role="dialog"
     aria-labelledby="ResetByEmailModal"
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
                <form action="/reset" class="reset-by-email">
                    <input type="hidden" name="token" value="{{$token}}">
                    <input type="hidden" name="email" value="{{$email}}">
                    <div class="form-group">
                        <label for="password">{{__('app.your_password')}}</label>
                        <input id="password" class="form-control" type="password" name="password"
                               value="" placeholder="">
                        <div class="invalid-feedback">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">{{__('app.your_password')}}</label>
                        <input id="confirm_password" class="form-control" type="password" name="confirm_password"
                               value="" placeholder="">
                        <div class="invalid-feedback">
                        </div>
                    </div>
                    <a class="reset-by-email-submit modal-submit font-weight-300 text-center text-uppercase d-block">{{__('app.accept')}}</a>
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
<script>
    $(document).ready(function () {
        $('#ResetByEmailModal').modal('show');
    });
</script>