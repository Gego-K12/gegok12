@extends('layouts.siteadmin.layout')

@section('content')
    <div class="bg-white shadow px-4 py-3">
        <div class="flex items-center justify-between mb-4">
            <h1 class="admin-h1 flex items-center">
                <a href="{{ url('/plugins') }}" title="Back" class="rounded-full bg-gray-100 p-2 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 492 492" class="w-3 h-3 fill-current text-gray-700"><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788C492,219.198,479.172,207.418,464.344,207.418z"/></svg>
                </a>
                {{ $plugin->name }} <span class="text-sm text-gray-500 ml-2">({{ $plugin->slug }})</span>
            </h1>
            <span class="text-xs font-semibold rounded px-2 py-1
                @if($plugin->status === 'installed') bg-green-100 text-green-700
                @elseif($plugin->status === 'failed') bg-red-100 text-red-700
                @elseif(in_array($plugin->status, ['installing', 'uninstalling'])) bg-blue-100 text-blue-700
                @elseif($plugin->status === 'uninstalled') bg-gray-100 text-gray-600
                @else bg-yellow-100 text-yellow-700 @endif">
                {{ ucfirst(str_replace('_', ' ', $plugin->status)) }}
            </span>
        </div>

        <pre class="text-xs whitespace-pre-wrap bg-gray-50 border rounded p-4 overflow-x-auto">{{ $plugin->cleanLog ?: 'No log output yet.' }}</pre>
    </div>
@endsection
