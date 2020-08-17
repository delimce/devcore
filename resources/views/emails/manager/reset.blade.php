@extends('layout.email.manager')
@section('content')
<h2 style="text-align: center">@lang('manager.email.reset.title')</h2>
<div style="padding-left: 14px">
    @lang('commons.hello')&nbsp;{{$data['name']}}.
    @lang('manager.email.reset.content'),&nbsp;
    <a style="color: #0c5460" href="{!! url("manager/reset/") !!}/{{$data["token"]}}">@lang('commons.go')</a>
</div>
@endsection