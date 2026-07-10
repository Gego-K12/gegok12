@extends('layouts.admin.layout')

@section('content')
    <div class="">
        <h1 class="admin-h1 my-3 flex items-center">
            <a href="{{ url('/admin/video-conference')  }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124 c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844 L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412 c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008 c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788 C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg>
            </a>
            <span class="mx-3">Details</span>
        </h1>
        @include('partials.message')
        <div class="flex flex-wrap custom-table my-3 overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-grey-light">
                        <tr class="border-b">
                            <th class="text-left text-sm px-2 py-2 text-grey-darker"> Name </th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker"> Start Time  </th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker"> Total Duration(Sec)  </th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker"> Duration  </th>
                        </tr>
                    </thead>   
                    <tbody >
                    @if(count($list)>0) 
                        @foreach($list as $key=>$participants)
                        <tr class="border-b" >
                       
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{$key}}</p>
                            </td>
                            <td width="40%" class="px-0 py-0">

                                @foreach($list[$key] as $values)
                                <div class="flex items-center border">
                                <p class="w-1/2 p-1">{{date('Y-m-d h:i:s', strtotime($values['start_time']))}}</p>
                                <p class="w-1/2 p-1 border-l">{{date('Y-m-d h:i:s', strtotime($values['end_time']))}}</p>
                                <p class="w-1/2 p-1 border-l">{{$values['duration']}}</p>
                                </div>
                                @endforeach
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{$total_duration[$key]['totalduration']}}</p>
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{$total_duration[$key]['duration']}}</p>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @else
                    <tbody>
                        <tr class="border-b">
                            <td colspan="4">
                                <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
                            </td>
                        </tr>
                    </tbody>
                    @endif
                </table>
            </div>
    </div>
@endsection