@extends('layout.defaultm')
@section('title',  $name)

@section('content')
<search-header-component></search-header-component>
<garage-detail-component :id="{{$id}}"></garage-detail-component>
<users-component></users-component>

@endsection