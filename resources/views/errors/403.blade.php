@extends('layout.default')
@section('title', __('errors.403'))
@section('content')
<Error-page-component :error_code="403" error_desc="{{__('errors.403')}}"></Error-page-component>   
@endsection