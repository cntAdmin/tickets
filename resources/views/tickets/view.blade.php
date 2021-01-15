@extends('layouts.admin')
@section('content')
    <h4 class="title text-uppercase">
        {{ __('Ticket') . ' (' . $ticket->custom_id . ')' }}
    </h4>

    <ticket-info class="shadow" :ticket="{{ $ticket }}"></ticket-info>
    @foreach ($ticket->comments as $comment)
        <ticket-comment :user_role="{{ $comment->user->getRoleNames() }}" username="{{$comment->user->name}}"
            :comment="{{ $comment }}" timestamp="{{ $comment->created_at->diffForHumans() }}"></ticket-comment>
    @endforeach
@endsection