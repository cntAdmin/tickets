@extends('layouts.admin')
@section('content')
    <h4 class="title">
        {{ __('Dashboard')}}
    </h4>
    {{-- TARJETAS CONTADORES DE TICKETS SEGUN ESTADOS--}}
    <div class="row">
        <card-ticket-counter count="" title="Total"></card-ticket-counter>
        @foreach ($ticket_statuses as $ts)
            <card-ticket-counter count="{{ $ts->id }}" title="{{ $ts->name }}"></card-ticket-counter>
        @endforeach

    </div>
    {{-- TARJETA PRINCIPAL CON BUSCADOR Y TABLAS --}}
    <card-tickets class="shadow"></card-tickets>
@endsection