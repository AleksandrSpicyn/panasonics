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
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <td>ID</td>
                            <td>{{__('app.title')}}</td>
                            <td>{{__('app.created_at')}}</td>
                            <td>{{__('app.status')}}</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($jobs AS $job)
                            <tr data-href="/job/{{$job["id"]}}">
                                <td>{{$job["id"]}}</td>
                                <td>{{$job["title"]}}</td>
                                <td>{{$job["created_at"]}}</td>
                                <td>{{__('app.'.$job["status"])}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <?php echo $jobs->render(); ?>
                </div>
            </div>
        </div>

    </section>
@endsection