@extends("admin.master")
@section("content")
    {!! Breadcrumbs::render('user.index') !!}
    <div class="wrapper" id="user_wrapper">
        <div class="row">
            <div class="col-md-12">
                {{ !empty(Session::get('msg'))? message(Session::get('msg')):"" }}
                {{errors($errors)}}
                <datagridview :head="head" :url="url"></datagridview>
            </div>
        </div>
    </div>
@endsection

@section("script")


    <script>

        var user_data = {
            url: "{{action('UserController@getIndex')}}",
            head: {
                id  : "#",
                name:    'نام',
                family:  'نام خانوادگی',
                email:   'ایمیل',
                type:    'نوع',
                sex:     'جنسیت',
                status:  'وضعیت'
            }
        }

    </script>
@endsection