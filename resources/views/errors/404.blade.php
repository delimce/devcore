@extends('layout.default')
@section('title', __('errors.404'))
@section('content')
<Error-page-component :error_code="404" error_desc="{{__('errors.404')}}"></Error-page-component>   
@endsection