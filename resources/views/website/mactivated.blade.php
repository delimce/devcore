@extends('layout.defaultm')
@section('title',  __('commons.activate'))

@section('content')
<Sign-up-component new-user="{{$username}}" :activated="true"></Sign-up-component>
@endsection