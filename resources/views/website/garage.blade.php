@extends('layout.default')
@section('title',  $name)

@section('content')
<header-search-component></header-search-component>
<garage-detail-component :id="{{$id}}"></garage-detail-component>

@endsection