@extends('layouts.admin.layout')
@section('content')
    <div class="">
        <div class="container-body">
            <h1 class="admin-h1 my-3 flex items-center">
                <a  href="{{ url('/admin/students') }}" class="rounded-full bg-gray-100 p-2" title="Back">
               <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124    c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412    c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008    c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg>
                </a>
                <span class="mx-3">Import</span>
            </h1>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading border border-gray-400 bg-white">
                <div class="flex flex-col">
                    <div class="mt-3">
                        <a href="{{ url('admin/downloadformat') }}" id="sample" class="no-underline text-white px-4 my-3 mx-3 flex items-center custom-green py-1 w-11/12 lg:w-1/5">Download Sample Format</a>
                    </div>
                </div>
                <form style="padding: 10px;margin-bottom: unset;" action="{{ url('admin/importUsers') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                    @csrf
                    @include('partials.message')
                    
                    {{-- Validation Errors Display --}}
                    @if (session('validation_errors'))
                    @php
                        // Group errors by type
                        $errorsByType = [];
                        foreach (session('validation_errors') as $error) {
                            foreach ($error['errors'] as $err) {
                                if (!isset($errorsByType[$err])) {
                                    $errorsByType[$err] = [];
                                }
                                $errorsByType[$err][] = [
                                    'row' => $error['row'],
                                    'student' => $error['student']
                                ];
                            }
                        }
                        // Sort by count (most common errors first)
                        uasort($errorsByType, function($a, $b) {
                            return count($b) - count($a);
                        });
                    @endphp
                    
                    <div class="bg-red-50 border border-red-300 rounded-lg p-4 mb-4">
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <h3 class="text-red-700 font-semibold text-sm">CSV Validation Failed - {{ count(session('validation_errors')) }} row(s) with issues</h3>
                        </div>
                        
                        {{-- TAB NAVIGATION --}}
                        <div class="flex border-b border-red-200 mb-3">
                            <button type="button" onclick="showTab('summary')" id="tab-summary" class="px-4 py-2 text-sm font-medium text-red-700 border-b-2 border-red-500 focus:outline-none">
                                Summary by Error Type
                            </button>
                            <button type="button" onclick="showTab('detailed')" id="tab-detailed" class="px-4 py-2 text-sm font-medium text-red-400 hover:text-red-600 focus:outline-none">
                                Detailed View
                            </button>
                        </div>
                        
                        {{-- SUMMARY VIEW (Grouped by Error Type) --}}
                        <div id="view-summary" class="max-h-96 overflow-y-auto">
                            @foreach ($errorsByType as $errorType => $rows)
                            <div class="mb-4 bg-white rounded border border-red-200 overflow-hidden">
                                <div class="bg-red-100 px-3 py-2 flex justify-between items-center">
                                    <span class="font-semibold text-red-700 text-sm">{{ $errorType }}</span>
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ count($rows) }} row(s)</span>
                                </div>
                                <div class="px-3 py-2">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($rows as $rowInfo)
                                        <span class="inline-flex items-center bg-red-50 border border-red-200 rounded px-2 py-1 text-xs" title="{{ $rowInfo['student'] }}">
                                            <span class="font-mono text-red-600">Row {{ $rowInfo['row'] }}</span>
                                            @if($rowInfo['student'])
                                            <span class="text-red-400 ml-1 max-w-24 truncate">- {{ $rowInfo['student'] }}</span>
                                            @endif
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        {{-- DETAILED VIEW (Table by Row) --}}
                        <div id="view-detailed" class="max-h-96 overflow-y-auto hidden">
                            <table class="w-full text-sm">
                                <thead class="bg-red-100 sticky top-0">
                                    <tr>
                                        <th class="text-left p-2 text-red-700 font-semibold w-16">Row</th>
                                        <th class="text-left p-2 text-red-700 font-semibold w-48">Student</th>
                                        <th class="text-left p-2 text-red-700 font-semibold">Errors</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (session('validation_errors') as $error)
                                    <tr class="border-b border-red-200 hover:bg-red-100">
                                        <td class="p-2 text-red-600 font-mono">{{ $error['row'] }}</td>
                                        <td class="p-2 text-red-600">{{ $error['student'] ?: '-' }}</td>
                                        <td class="p-2 text-red-600">
                                            <ul class="list-disc list-inside">
                                                @foreach ($error['errors'] as $err)
                                                <li>{{ $err }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3 pt-3 border-t border-red-200">
                            <p class="text-red-600 text-xs">
                                <strong>Summary:</strong> {{ count($errorsByType) }} unique error type(s) across {{ count(session('validation_errors')) }} row(s).
                                Fix the errors in your CSV file and try again.
                            </p>
                        </div>
                    </div>
                    
                    <script>
                        function showTab(tab) {
                            // Hide all views
                            document.getElementById('view-summary').classList.add('hidden');
                            document.getElementById('view-detailed').classList.add('hidden');
                            
                            // Reset all tab styles
                            document.getElementById('tab-summary').classList.remove('border-b-2', 'border-red-500', 'text-red-700');
                            document.getElementById('tab-summary').classList.add('text-red-400');
                            document.getElementById('tab-detailed').classList.remove('border-b-2', 'border-red-500', 'text-red-700');
                            document.getElementById('tab-detailed').classList.add('text-red-400');
                            
                            // Show selected view and style tab
                            document.getElementById('view-' + tab).classList.remove('hidden');
                            document.getElementById('tab-' + tab).classList.add('border-b-2', 'border-red-500', 'text-red-700');
                            document.getElementById('tab-' + tab).classList.remove('text-red-400');
                        }
                    </script>
                    
                    {{ session()->forget('validation_errors') }}
                    @endif
                    
                    {{-- Validation Warnings Display (shown alongside errors) --}}
                    @if (session('validation_warnings') && count(session('validation_warnings')) > 0)
                    <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-4 mb-4">
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <h3 class="text-yellow-700 font-semibold text-sm">Warnings - These will be auto-handled during import</h3>
                        </div>
                        <div class="max-h-48 overflow-y-auto">
                            @foreach (session('validation_warnings') as $warning)
                            <div class="bg-white border border-yellow-200 rounded p-2 mb-2 text-sm">
                                <span class="font-mono text-yellow-600">Row {{ $warning['row'] }}</span>
                                <span class="text-yellow-700 ml-2">{{ $warning['student'] }}</span>
                                <ul class="list-disc list-inside text-yellow-600 mt-1">
                                    @foreach ($warning['warnings'] as $w)
                                    <li>{{ $w }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    {{ session()->forget('validation_warnings') }}
                    @endif
                    
                    {{-- Import Summary (shown after successful import) --}}
                    @if (session('import_summary'))
                    @php $summary = session('import_summary'); @endphp
                    <div class="bg-green-50 border border-green-300 rounded-lg p-4 mb-4">
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <h3 class="text-green-700 font-semibold text-sm">Import Summary</h3>
                        </div>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div class="bg-white rounded-lg p-3 border border-green-200">
                                <div class="text-2xl font-bold text-green-600">{{ $summary['students_imported'] ?? 0 }}</div>
                                <div class="text-xs text-green-700">Students Imported</div>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-green-200">
                                <div class="text-2xl font-bold text-blue-600">{{ $summary['parents_created'] ?? 0 }}</div>
                                <div class="text-xs text-blue-700">New Parents Created</div>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-green-200">
                                <div class="text-2xl font-bold text-purple-600">{{ $summary['siblings_linked'] ?? 0 }}</div>
                                <div class="text-xs text-purple-700">Siblings Linked to Existing Parents</div>
                            </div>
                        </div>
                        @if (($summary['siblings_linked'] ?? 0) > 0)
                        <p class="text-green-600 text-xs mt-3">
                            <strong>Note:</strong> {{ $summary['siblings_linked'] }} student(s) were linked to existing parent accounts (same parent email).
                        </p>
                        @endif
                    </div>
                    {{ session()->forget('import_summary') }}
                    @endif
                    
                    {{ session()->forget('count') }}
                    {{ session()->forget('insertedcount') }}
                    <div class="flex flex-col">
                        <div>
                            <input type="file" id="file" name="import_file">
                        </div>
                        <span class="text-red-500 text-xs font-semibold">{{$errors->first('import_file')}}</span> 
                        <div class="mt-3">
                            <button class="btn btn-primary submit-btn" id="import">Import</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
 @endsection
