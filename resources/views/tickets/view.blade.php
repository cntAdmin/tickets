@extends('layouts.admin')
@section('content')
    <h4 class="title text-uppercase">
        {{ __('Ticket') . ' (' . $ticket->custom_id . ')' }}
    </h4>
    <ticket-view-info class="shadow" :ticket="{{ $ticket }}"></ticket-view-info>
    @if($ticket->calls->count() > 0) 
        <ticket-view-calls :calls="{{ $ticket->calls }}"></ticket-view-calls>
    @endif
    @foreach ($ticket->comments as $comment)
        <ticket-comment :user_role="{{ $comment->user->getRoleNames() }}" username="{{$comment->user->name}}"
            :comment="{{ $comment }}" timestamp="{{ $comment->created_at->diffForHumans() }}"></ticket-comment>
    @endforeach
    <ticket-new-coment :user_role="{{ auth()->user()->getRoleNames() }}" :ticket_id='{{ $ticket->id }}'></ticket-new-coment>
@endsection