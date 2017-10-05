@extends("admin.master")
@section("content")
    {!! Breadcrumbs::render('user.edit') !!}
    @include("admin.users.form", ["action"=>action('UserController@postUpdate', $user->id),
    "user"=>$user, "button"=>"ذخیره"])
@endsection