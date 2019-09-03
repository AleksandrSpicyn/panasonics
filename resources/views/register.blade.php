<div class="modal fade front-modal" id="RegisterModal" tabindex="-1" role="dialog" aria-labelledby="RegisterModal"
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
                            <h5 class="modal-title" id="exampleModalLabel">{{__('app.register_title')}}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form action="" class="register">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="first_name">{{__('app.your_first_name')}}</label>
                                    <input id="first_name" class="form-control" type="text" name="first_name"
                                           value="">
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="second_name">{{__('app.your_second_name')}}</label>
                                    <input id="second_name" class="form-control" type="text" name="second_name"
                                           value="">
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label for="second_name">{{__('app.birth_date')}}</label>
                                <div class="input-group form-group">
                                    <input type="text" class="form-control" name="day"
                                           placeholder="{{__('app.day')}}">
                                    <input type="text" class="form-control" name="month"
                                           placeholder="{{__('app.month')}}">
                                    <input type="text" class="form-control" name="year"
                                           placeholder="{{__('app.year')}}">
                                    <input type="hidden" name="date_of_birth" class="form-control">
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="second_name">{{__('app.gender')}}</label>
                                <div class="form-group mt-2">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="customRadio"
                                               name="gender" value="{{App\User::GENDER_MEN}}" checked>
                                        <label class="custom-control-label"
                                               for="customRadio">{{__('app.gender_men')}}</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="customRadio2"
                                               name="gender" value="{{App\User::GENDER_WOMEN}}">
                                        <label class="custom-control-label"
                                               for="customRadio2">{{__('app.gender_woman')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="email">{{__('app.email')}}</label>
                                    <input id="email" class="form-control" type="text" name="email"
                                           value="">
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group ">
                                    <label for="password">{{__('app.your_password')}}</label>
                                    <div class="prmy">
                                        <input id="password" class="form-control" type="password" name="password"
                                               value="" placeholder="">
                                        <div class="invalid-feedback">
                                        </div>
                                        <span toggle="#password" class="field-icon toggle-password"></span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 pt-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customCheck"
                                           name="newsletter">
                                    <label class="custom-control-label" for="customCheck">
                                        {{__('app.newsletter')}}
                                    </label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 pt-3">
                                <a class="modal-submit submit-register font-weight-300 text-center text-uppercase">{{__('app.save')}}</a>
                                <div>
                                    <input type="hidden" name="all_errors" class="form-control">
                                    <div class="invalid-feedback text-center">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
