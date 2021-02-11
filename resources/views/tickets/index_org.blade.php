@extends('layouts.admin')
@section('content')
    <h4 class="title">
        {{ __('Dashboard')}}
    </h4>
    <tickets status="{{ $status }}"/>
@endsection