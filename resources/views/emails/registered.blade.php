@extends('layout.email.manager')
@section('content')
<h2 style="text-align: center">@lang('manager.email.registered.title')</h2>
<div style="padding-left: 14px">
    @lang('manager.email.registered.content'),&nbsp;
    <a style="color: #0c5460" href="{!! url("manager/activate/") !!}/ {{$user->token}}">@lang('commons.go')</a>
</div>
@endsection