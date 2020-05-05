@extends('layout.manager')
@section('title',  __('manager.admin'))

@section('content')

 <!-- START NAV -->
 <navbar-component></navbar-component>
 <!-- END NAV -->

<div class="columns" id="app-content">
    <div class="column is-2 is-fullheight is-hidden-touch" id="navigation">
       <menu-component></menu-component>
    </div>

    <div class="column is-10" id="page-content">
       <header-component></header-component>
       <router-view></router-view>
    </div>
</div>

@endsection