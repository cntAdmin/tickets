@extends('layouts.app')
@section('content')
<div class="m-5">
    <div class="card">
        <div class="card-header">
            <div class="row justify-content-center">
                <div class="mr-xl-auto col-12 col-xl-6">
                    <h4 class="title text-uppercase">
                        Incidencia:
                        <span class="font-weight-bold">( {{ $ticket->custom_id }} )</span>
                    </h4>
                </div>
            </div>
        </div>
        <div class="card-body">
            <ticket-view :user_role="5" :ticket="{{ $ticket }}"></ticket-view>
            
            @if($ticket->attachments->count() > 0)
            <div class="row justify-content-end mx-1">
                @foreach($ticket->attachments as $attachment)
                <div>
                    <a :href="`/api/download/ticket/${ticket.id}/file/${attachment.id}`"
                        class="btn btn-sm btn-success shadow font-weight-bold mr-2 my-2">
                        {{ $attachment->name ? $attachment->name : $attachment->path }}
                    </a>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @if($ticket->comments->count() > 0)
    <ticket-comments :comments="{{ $ticket->comments }}" user_role="5"></ticket-comments>
    @endif

    <div class="row justify-content-center">
        <ticket-new-coment :ticket_id="{{ $ticket->id }}" user_role="5"></ticket-new-coment>
    </div>
</div>
@endsection