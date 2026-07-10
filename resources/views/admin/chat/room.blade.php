@extends('layouts.admin.layout')

@section('content')
    <div class="">
        <div class="flex py-3 justify-between">
            <h1 class="admin-h1 my-3 flex items-center">
                <a href="{{ url('/admin/chat') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124 c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412 c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008 c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg>
                </a>
                <span class="mx-3">{{ $room->title }}</span>
            </h1>
            <div class="my-2 lg:my-3">
                <a href="{{ url('/admin/room/addMember/'.$room->id) }}" id="add_member" class="capitalize text-white custom-green rounded px-2 py-1 mr-2 font-medium">add member</a>

                <a href="{{ url('/admin/room/edit/'.$room->id) }}" id="edit" class="capitalize text-white blue-bg rounded px-2 py-1 mr-2 font-medium">edit group</a>

                <a href="#" rel="{{ url('/admin/room/delete/'.$room->id) }}" id="delete_group" class="capitalize text-white bg-red-600 rounded px-2 py-1 mr-2 font-medium delete-group">delete group</a>
            </div>
        </div>
        @include('admin.chat.__main')
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.delete-group').on('click', function(){
                var link = $(this).attr('rel');
                var status = $(this).attr('value');
                //alert(status);
                swal({
                    icon: "info",
                    text: "Do you want to delete this Chat Room ?",
                    buttons: {
                        cancel: true,
                        confirm: true,
                    },
                    allowOutsideClick: false,
                }).then((willChange) => {
                    if (willChange)
                    {
                        $.ajax({
                            url: link,
                            data: { status: status },
                            type: "GET",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success:function(data)
                            {
                                //alert(ans);
                                swal({
                                    icon: "success",
                                    text: "Chat Room Deleted Successfully",
                                }).then(function(){
                                    window.location.href = '/admin/chat';
                                });
                            }
                        })
                    }
                    else
                    {
                        swal("Cancelled");
                    }
                });
            });
        });

        $(document).ready(function(){
            $('.delete-member').on('click', function(){
                var link = $(this).attr('rel');
                //alert(link);
                swal({
                    icon: "info",
                    text: "Do you want to remove the user from this chat room ?",
                    buttons: {
                        cancel: true,
                        confirm: true,
                    },
                    allowOutsideClick: false,
                }).then((willChange) => {
                    if (willChange)
                    {
                        $.ajax({
                            url: link,
                            type: "GET",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success:function(data)
                            {
                                //alert(ans);
                                 swal({
                                    icon: "success",
                                    text: "Member Removed from this chat room",
                                }).then(function(){
                                    window.location.reload();
                                });
                            }
                        })
                    }
                    else
                    {
                        swal("Cancelled");
                    }
                });
            });
        });
    </script>

    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('03486db0031d182cbba5', {
          cluster: 'mt1'
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('chat.1', function(data) {
          app.messages.push(JSON.stringify(data));
        });
    </script>
@endpush