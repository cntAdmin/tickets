@extends('layouts.admin')
@section('content')
    <h4 class="title">
        {{ __('Dashboard')}}
    </h4>
    {{-- TARJETAS CONTADORES DE TICKETS SEGUN ESTADOS--}}
    <div class="row">
        <tickets-card-counter count="" title="Total"></tickets-card-counter>
        @foreach ($ticket_statuses as $ts)
            <tickets-card-counter count="{{ $ts->id }}" title="{{ $ts->name }}"></tickets-card-counter>
        @endforeach

    </div>
    {{-- TARJETA PRINCIPAL CON BUSCADOR Y TABLAS --}}
    <tickets-card class="shadow"></tickets-card>
@endsection