@extends('layouts.admin.layout')

@section('content')
@include('partials.message')

<div class="flex flex-wrap lg:flex-row justify-between items-center" x-data="{ showCreateMenu: false }">
    <div>
        <h1 class="admin-h1 my-3">Events ({{ $count }})</h1>
    </div>
    <div class="relative flex items-center w-8/12 lg:w-1/4 md:w-1/4 justify-end">
        <div class="flex items-center w-full justify-end mx-2">
            <form method="GET" action="{{ url('/admin/events') }}" class="flex items-center w-full justify-end">
                <select name="standardLink_id" onchange="this.form.submit()" class="tw-form-control w-1/2 mx-2">
                    <option value="" disabled {{ $standard ? '' : 'selected' }}>Select Class</option>
                    @foreach($standardlist as $list)
                    <option value="{{ $list->id }}" {{ (string) $standard === (string) $list->id ? 'selected' : '' }}>{{ $list->standard_section }}</option>
                    @endforeach
                </select>
            </form>
            <a href="{{ url('/admin/events') }}" class="text-sm border bg-gray-100 text-grey-darkest py-1 px-4 mx-1">Reset</a>
        </div>
        <div class="w-32 relative" style="width: 12rem;">
            <a href="#" x-on:click.prevent="showCreateMenu = !showCreateMenu" class="text-sm rounded px-2 py-1 flex items-center whitespace-no-wrap justify-between btn btn-primary submit-btn w-full">
                <span>Create Event</span>
            </a>
            <div class="border absolute z-40 w-40 right-0 bg-white" x-show="showCreateMenu" x-on:click.outside="showCreateMenu = false">
                <ul class="list-reset text-xs text-gray-700 leading-loose py-1">
                    <li class="px-2"><a href="#" class="whitespace-no-wrap" x-on:click.prevent="showCreateMenu = false; Livewire.dispatch('openCreateEvent', { selectType: 'class' })">Create Class Room Event</a></li>
                    <li class="px-2"><a href="#" x-on:click.prevent="showCreateMenu = false; Livewire.dispatch('openCreateEvent', { selectType: 'school' })">Create School Event</a></li>
                    @if(config('galumni.enabled'))
                    <li class="px-2"><a href="#" x-on:click.prevent="showCreateMenu = false; Livewire.dispatch('openCreateEvent', { selectType: 'alumni' })">Create Alumni Event</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

@livewire('admin.events.event-form')

<div class="py-5">
    <div id="event-calendar" x-data x-init="window.initEventCalendar('event-calendar', @js($events))"></div>
</div>
<event-popup :url="this.url" mode="admin"></event-popup>
<div id="eventpopup"></div>
@endsection

@push('scripts')
<script src="{{ mix('js/calendar.js') }}"></script>
@endpush