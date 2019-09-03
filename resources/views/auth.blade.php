<div class="modal fade front-modal" id="AuthModal" tabindex="-1" role="dialog" aria-labelledby="AuthModal"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content" id="auth">
            <div class="modal-header">
                <a class="close-modal" data-dismiss="modal" aria-label="Close">
                    <img src="/assets/img/cross.png" alt="">
                </a>
                <h5 class="modal-title" id="exampleModalLabel">{{__('app.auth')}}</h5>
            </div>
            <div class="modal-body ">
                <form action="/auth" class="auth">
                    <div class="form-group">
                        <input id="email" class="form-control" type="text" name="email"
                               value="" placeholder="E-mail">
                        <div class="invalid-feedback">
                        </div>
                    </div>
                    <div class="form-group prmy">
                        <input id="password" class="form-control" type="password" name="password"
                               value="" placeholder="{{__('app.password')}}">
                        <div class="invalid-feedback">
                        </div>
                        <span toggle="#password" class="field-icon toggle-password"></span>
                    </div>
                    <input type="hidden" name="all_errors" class="form-control">
                    <div class="invalid-feedback text-center">
                    </div>
                    <a class="modal-submit modal-auth font-weight-300 text-center text-uppercase d-block submit-auth">{{__('app.entry')}}</a>
                    <a class="modal-register font-weight-300 text-center text-uppercase d-block register_button"
                       id="changeFormToRegister">{{__('app.in_register')}}</a>
                    <a href="#"
                       class="mt-2 d-block main-color text-uppercase text-center font-weight-500 forgot-password reset_pass"
                       id="changeFormToReset">{{__('app.forgot_password')}}</a>
                </form>
            </div>
        </div>
    </div>
</div>
