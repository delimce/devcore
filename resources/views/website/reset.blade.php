@extends('layout.default')
@section('title',  __('commons.password.reset'))

@section('content')
<reset-password-component token="{{$token}}"></reset-password-component>
@endsection