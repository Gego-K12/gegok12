@extends('layouts.alumni.layout')

@section('content')
    <div class="py-5">
        <show-event url="{{ url('/') }}" count="{{ $count }}" no_of_events="{{ $subscription->plan->no_of_events }}" events="{{ $events }}" mode="alumni"></show-event>
    </div>
    <event-popup url="{{ url('/') }}" mode="alumni"></event-popup>
    <div id="eventpopup"></div>
@endsection