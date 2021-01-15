@extends('layouts.admin')
@section('content')
    <h4 class="title">
        {{ __('Nuevo ticket')}}
    </h4>
    <div class="row">
        <tickets-create></tickets-create>
    </div>
@endsection