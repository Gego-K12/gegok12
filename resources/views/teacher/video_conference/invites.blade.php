@extends('layouts.teacher.layout')
@section('content')
    <div class="w-full">
        <div>
            <h1 class="admin-h1 my-3 flex items-center">
                <a href="{{ url('/teacher/video-conference') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124 c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412 c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008 c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg>
                </a>
                <span class="mx-3">Manage Invitees - {{ $conference->name }}</span>
                <div class="relative flex items-center w-3/4 lg:justify-end">
                    <div class="flex items-center" dusk="add-button">
                        <a href="{{ url('teacher/video-conference/add-invites/'.$conference->id) }}" class="no-underline text-white  px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">
                            <span class="mx-1 text-sm font-semibold">Add Invitees</span>
                            <svg class="w-3 h-3 fill-current text-white" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 409.6 409.6" style="enable-background:new 0 0 409.6 409.6;" xml:space="preserve"><g><g><path d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067 C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067 s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"/></g></g></svg>
                        </a> 
                    </div>
                </div>
            </h1>
        </div>
        <div class="bg-gray-200 flex-grow w-full">
            <div class="relative">
                @include('partials.message')
                <div>
                    <div class="flex flex-wrap custom-table my-3 overflow-auto">
                        <table class="w-full">
                            <thead class="bg-grey-light">
                                <tr class="border-b">
                                    <th class="text-left text-sm px-2 py-2 text-grey-darker"> S.No </th>
                                    <th class="text-left text-sm px-2 py-2 text-grey-darker"> Name </th>
                                    <th class="text-left text-sm px-2 py-2 text-grey-darker"> Roll Number </th>
                                    <th class="text-left text-sm px-2 py-2 text-grey-darker"> Email </th>
                                    <th class="text-left text-sm px-2 py-2 text-grey-darker"> Actions </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($getParticipant)>0)
                                    @php 
                                        $i = ($getParticipant->currentPage() - 1) * $getParticipant->perPage() + 1; 
                                    @endphp
                                    @foreach($getParticipant as $info)
                                        <tr class="border-b">
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $info->usersInfo->FullName }}</td>
                                            <td>{{ $info->usersInfo->studentAcademicLatest->roll_number }}</td>
                                            <td>{{ $info->usersInfo->email }}</td>
                                            <td>@if($conference->user_id==Auth::user()->id)<a href="javascript:void(0);" class="btn bg-red-600 text-white rounded px-3 py-1 delete" data-id="{{$info->id}}">Delete</a>@else - @endif</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="border-b">
                                        <td colspan="4">
                                            <p class="font-semibold text-s" style="text-align: center;">No Records Found</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        {{$getParticipant->links('layouts.pagination',array('search'=>$build))}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function(){
            $(document).on('click', '.delete', function(){
                var r = confirm("Are you sure to delete?");
                if (r == true) {
                    var getid = $(this).data('id');
                    window.location.href = '{{url("teacher/video-conference/remove-users")}}/'+getid;
                }
            });
        });
    </script>
@endpush 