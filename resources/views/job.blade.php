@extends('app')
@section('content')
    @component('header')
    @endcomponent
    <section id="content">
        <div class="container">
            <div class="row">
                <div class="col-4">
                    @component('menu')
                    @endcomponent
                </div>
                <div class="col-8">
                    <form action="" class="admin-job">
                        <input id="id" class="" type="hidden" name="id" value="{{$job["id"]}}">
                        <div class="form-group">
                            <label for="title">{{__('app.title')}}</label>
                            <input id="title" class="form-control" type="text" name="title" value="{{$job["title"]}}">
                        </div>
                        <div class="form-group">
                            <label for="created_at">{{__('app.created_at')}}</label>
                            <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                <input id="created_at" type="text" class="form-control datetimepicker-input"
                                       data-target="#datetimepicker1" name="created_at"
                                       value="{{ date('Y-m-d H:i',strtotime($job["created_at"])) }}"/>
                                <div class="input-group-append" data-target="#datetimepicker1"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="likes">{{__('app.likes')}}</label>
                            <input id="likes" class="form-control" type="text" name="" disabled value="{{$likes}}">
                        </div>
                        <div class="form-group">
                            <label for="email">{{__('app.email')}}</label>
                            <input id="email" class="form-control" type="text" name="" disabled
                                   value="{{$job["user"]['email']}}">
                        </div>
                        <div class="form-group">
                            <label for="fio">{{__('app.fio')}}</label>
                            <input id="fio" class="form-control" type="text" name="" disabled
                                   value="{{$job["user"]['first_name'].' '.$job["user"]['second_name']}}">
                        </div>
                        {{--                        <div class="form-group">--}}
                        {{--                            <label for="title">Проголосовавшие пользователи:</label>--}}
                        {{--                            <ul class="list-unstyled">--}}
                        {{--                                @foreach($likeUsers AS $like)--}}
                        {{--                                    <li>{{$like["user"]["email"]}}</li>--}}
                        {{--                                @endforeach--}}
                        {{--                            </ul>--}}
                        {{--                        </div>--}}
                        <div class="form-group">
                            <label for="description">{{__('app.description')}}</label>
                            <textarea class="form-control" name="description" id="description" cols="30"
                                      rows="3">{{$job["description"]}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="status">{{__('app.status')}}</label>
                            <select id="status" class="form-control" name="status">
                                <option @if(App\Job::JOB_ACCEPT_STATUS == $job["status"]) selected
                                        @endif value="{{App\Job::JOB_ACCEPT_STATUS}}">{{ __('app.'.App\Job::JOB_ACCEPT_STATUS) }}</option>
                                <option @if(App\Job::JOB_WAIT_STATUS == $job["status"]) selected
                                        @endif value="{{App\Job::JOB_WAIT_STATUS}}">{{ __('app.'.App\Job::JOB_WAIT_STATUS) }}</option>
                                <option @if(App\Job::JOB_REFUSE_STATUS == $job["status"]) selected
                                        @endif value="{{App\Job::JOB_REFUSE_STATUS}}">{{ __('app.'.App\Job::JOB_REFUSE_STATUS) }}</option>
                                <option @if(App\Job::JOB_DELETE_STATUS == $job["status"]) selected
                                        @endif value="{{App\Job::JOB_DELETE_STATUS}}">{{ __('app.'.App\Job::JOB_DELETE_STATUS) }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="comment">{{__('app.refused_comment')}}</label>
                            <textarea class="form-control" name="comment" id="comment" cols="30"
                                      rows="3">{{$job["comment"]}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="uploadimage">{{__('app.download_image')}}</label>
                            <input type="file" id="uploadimage" name="image">
                            <img class="img-fluid job-admin-image" src="{{Storage::url($job["image"])}}" alt="">
                            <div>
                                <p>
                                    <a target="_blank" class="d-inline-block mr-2  mt-2 btn btn-info"
                                       href="https://yandex.ru/images/search?url={{Storage::url($job["image"])}}&rpt=imageview">{{__('app.image_unique')}}
                                    </a>
                                </p>
                                <a href="#" class="image-rotate d-inline-block mr-2  mb-2 btn btn-info"
                                   data-degree="-90" data-job="{{$job["id"]}}">
                                    Повернуть по часовой
                                </a>
                                <a href="#" class="image-rotate d-inline-block mr-2  mb-2 btn btn-info" data-degree="90"
                                   data-job="{{$job["id"]}}">
                                    Повернуть против часовой
                                </a>
                                <p>Внимание! После поворота ориентация картинки сохранится без нажатия на кнопку
                                    сохранения</p>
                            </div>


                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary submit-admin-job">{{__('app.save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection