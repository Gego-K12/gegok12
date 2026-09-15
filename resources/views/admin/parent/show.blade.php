@extends('layouts.admin.layout')

@section('content')
    <div class="">
        @include('partials.message')
        <div>
            <h1 class="admin-h1 my-3 flex items-center">
                <a href="{{ url('/admin/parents') }}" class="rounded-full bg-gray-100 p-2" title="Back">
                    <svg class="w-3 h-3 fill-current text-gray-700" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" style="enable-background:new 0 0 492 492;" xml:space="preserve" width="512px" height="512px"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124 c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844 L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412 c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008 c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788 C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" class="active-path" fill=""/></g></g></g> </svg>
                </a>
                <span class="mx-3">Parent Profile</span>
            </h1>
        </div>
        <div class="flex lg:items-center lg:justify-between flex-col lg:flex-row mx-5">
            <h3 class="font-semibold text-3xl text-gray-700">{{ ucwords($user->FullName) }}</h3>
            <div class="my-3 flex flex-wrap text-xs"> 
                <a href="{{url('/admin/parent/edit/'.$user->name)}}" title="Edit Member" class="capitalize text-white blue-bg rounded px-2 py-1 font-medium text-xs my-1 lg:my-0 md:my-0 flex items-center mr-2" id="edit">
                    <svg class="w-3 h-3 fill-current text-white" id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g><path d="m128.285 260.925h319.073v75h-319.073z" transform="matrix(.707 -.707 .707 .707 -126.717 290.929)"/><path d="m29.021 422.521-29.021 89.479 89.481-29.02z"/><path d="m54.039 186.679h319.073v75h-319.073z" transform="matrix(.707 -.707 .707 .707 -95.964 216.682)"/><path d="m371.541 5.46h90v180h-90z" transform="matrix(.707 -.707 .707 .707 54.502 322.498)"/><path d="m57.148 335.796-17.737 54.689 82.106 82.105 54.689-17.737z"/></g></svg>
                    <span class="mx-1">Edit</span>
                </a>

                <form id="parent_delete" action="{{ url('/admin/parent/delete', ['name'=>$user->name]) }}" method="POST" class="capitalize text-white blue-bg rounded px-2 py-1 font-medium text-xs my-1 lg:my-0 md:my-0 flex items-center mr-2" id="delete">
                    @csrf
                    @method('delete')
                                   
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-3 h-3 fill-current text-white"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon> <path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
                    <button type="button" class="mx-1 deleteuser ">Delete</button>
                </form>

               <!--  <a href="#" rel="{{ url('/admin/parent/delete/'.$user->name) }} " class="capitalize text-white custom-green rounded px-2 py-1 mr-2 font-medium deleteuser my-1 lg:my-0 md:my-0 mr-2" value="active" id="status">Delete</a> -->


                @if(optional($user)->status == "inactive")
                    <a href="#" rel="{{ url('/admin/user/updateStatus/'.$user->name) }} " class="capitalize text-white custom-green rounded px-2 py-1 mr-2 font-medium activate my-1 lg:my-0 md:my-0 mr-2" value="active" id="status">Activate</a>
                @else

                    <a href="#" rel="{{ url('/admin/user/updateStatus/'.$user->name) }}" class="capitalize text-white bg-red-600 rounded px-2 py-1 mr-2 font-medium activate my-1 lg:my-0 md:my-0 mr-2" value="inactive" id="status">Deactivate</a>
                @endif
                    
                @if($user->email != null)
                    <a href="#" rel="{{ url('/admin/user/resetPassword/'.$user->id) }}" class="capitalize text-white blue-bg rounded px-2 py-1 mr-2 font-medium reset my-1 lg:my-0 md:my-0">reset Password</a>

                    @if($user->email_verified != 1)
                        <a href="#" rel="{{ url('/admin/user/'.$user->id.'/verificationcode') }}" class="capitalize text-white blue-bg rounded px-2 py-1 mr-2 font-medium verify" id="verify_mail">verify email</a>
                    @endif
                @endif


            </div>
        </div>
        <div class="leading-relaxed mx-5 flex justify-between">
            <p class="text-lg text-gray-700 font-semibold">ID: {{ $user->id }}</p>
            <change-credential url="{{('/')}}" name="{{$user->name}}"  ></change-credential>
        </div>
        <div class="flex flex-col lg:flex-row md:flex-row mx-5">
            <div class="w-full lg:w-1/5 md:w-1/5 py-1">
                <!-- start -->
                <div class="bg-white rounded leading-relaxed">
                    <div class="my-4 px-2">
                        <p class="capitalize text-gray-800 text-sm">contact information :</p>
                        <ul class="list-reset text-xs leading-loose my-2">
                            <li class="flex py-1 items-center">
                                <svg class="fill-current w-3 h-3 text-gray-800" height="512" viewBox="0 0 58 58" width="512" xmlns="http://www.w3.org/2000/svg"><g id="Page-1" fill="none" fill-rule="evenodd"><g id="003---Call" fill="rgb(0,0,0)" fill-rule="nonzero" transform="translate(-1)"><path id="Shape" d="m25.017 33.983c-5.536-5.536-6.786-11.072-7.068-13.29-.0787994-.6132828.1322481-1.2283144.571-1.664l4.48-4.478c.6590136-.6586066.7759629-1.685024.282-2.475l-7.133-11.076c-.5464837-.87475134-1.6685624-1.19045777-2.591-.729l-11.451 5.393c-.74594117.367308-1.18469338 1.15985405-1.1 1.987.6 5.7 3.085 19.712 16.855 33.483s27.78 16.255 33.483 16.855c.827146.0846934 1.619692-.3540588 1.987-1.1l5.393-11.451c.4597307-.9204474.146114-2.0395184-.725-2.587l-11.076-7.131c-.7895259-.4944789-1.8158967-.3783642-2.475.28l-4.478 4.48c-.4356856.4387519-1.0507172.6497994-1.664.571-2.218-.282-7.754-1.532-13.29-7.068z"/><path id="Shape" d="m47 31c-1.1045695 0-2-.8954305-2-2-.0093685-8.2803876-6.7196124-14.9906315-15-15-1.1045695 0-2-.8954305-2-2s.8954305-2 2-2c10.4886126.0115735 18.9884265 8.5113874 19 19 0 1.1045695-.8954305 2-2 2z"/><path id="Shape" d="m57 31c-1.1045695 0-2-.8954305-2-2-.0154309-13.800722-11.199278-24.9845691-25-25-1.1045695 0-2-.8954305-2-2s.8954305-2 2-2c16.008947.01763587 28.9823641 12.991053 29 29 0 .530433-.2107137 1.0391408-.5857864 1.4142136-.3750728.3750727-.8837806.5857864-1.4142136.5857864z"/></g></g></svg>
                                <div class="w-full mx-2">
                                    <a href="#" class="blue-text">
                                        @if($user->mobile_no != null)
                                            {{ $user->mobile_no }}
                                        @else
                                            --
                                        @endif
                                    </a>
                                </div>
                            </li>
                            @if($user->userprofile->alternate_no != null)
                                <li class="flex py-1 items-center">
                                    <svg class="fill-current w-3 h-3 text-gray-800" height="512" viewBox="0 0 58 58" width="512" xmlns="http://www.w3.org/2000/svg"><g id="Page-1" fill="none" fill-rule="evenodd"><g id="003---Call" fill="rgb(0,0,0)" fill-rule="nonzero" transform="translate(-1)"><path id="Shape" d="m25.017 33.983c-5.536-5.536-6.786-11.072-7.068-13.29-.0787994-.6132828.1322481-1.2283144.571-1.664l4.48-4.478c.6590136-.6586066.7759629-1.685024.282-2.475l-7.133-11.076c-.5464837-.87475134-1.6685624-1.19045777-2.591-.729l-11.451 5.393c-.74594117.367308-1.18469338 1.15985405-1.1 1.987.6 5.7 3.085 19.712 16.855 33.483s27.78 16.255 33.483 16.855c.827146.0846934 1.619692-.3540588 1.987-1.1l5.393-11.451c.4597307-.9204474.146114-2.0395184-.725-2.587l-11.076-7.131c-.7895259-.4944789-1.8158967-.3783642-2.475.28l-4.478 4.48c-.4356856.4387519-1.0507172.6497994-1.664.571-2.218-.282-7.754-1.532-13.29-7.068z"/><path id="Shape" d="m47 31c-1.1045695 0-2-.8954305-2-2-.0093685-8.2803876-6.7196124-14.9906315-15-15-1.1045695 0-2-.8954305-2-2s.8954305-2 2-2c10.4886126.0115735 18.9884265 8.5113874 19 19 0 1.1045695-.8954305 2-2 2z"/><path id="Shape" d="m57 31c-1.1045695 0-2-.8954305-2-2-.0154309-13.800722-11.199278-24.9845691-25-25-1.1045695 0-2-.8954305-2-2s.8954305-2 2-2c16.008947.01763587 28.9823641 12.991053 29 29 0 .530433-.2107137 1.0391408-.5857864 1.4142136-.3750728.3750727-.8837806.5857864-1.4142136.5857864z"/></g></g></svg>
                                    <div class="w-full mx-2">
                                        <a href="#" class="blue-text">{{ $user->userprofile->alternate_no }}</a>
                                    </div>
                                </li>
                            @endif
                            <li class="flex py-1 items-center">
                                <svg class="w-3 h-3 fill-current text-gray-800" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g><polygon points="339.392,258.624 512,367.744 512,144.896"/></g></g><g><g><polygon points="0,144.896 0,367.744 172.608,258.624"/></g></g><g><g><path d="M480,80H32C16.032,80,3.36,91.904,0.96,107.232L256,275.264l255.04-168.032C508.64,91.904,495.968,80,480,80z"/></g></g><g><g><path d="M310.08,277.952l-45.28,29.824c-2.688,1.76-5.728,2.624-8.8,2.624c-3.072,0-6.112-0.864-8.8-2.624l-45.28-29.856 L1.024,404.992C3.488,420.192,16.096,432,32,432h448c15.904,0,28.512-11.808,30.976-27.008L310.08,277.952z"/></g></g></svg>
                                <div class="w-full mx-2">
                                    @if($user->email == '')
                                        --
                                    @else
                                        <a href="#" class="blue-text">{{ $user->email }}</a>
                                    @endif
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- end -->
            </div>
            <div class="w-full lg:w-4/5 md:w-4/5 lg:mx-8 md:mx-8">
                <div class="bg-white shadow my-5">
                    <profile-tab-parent url="{{ url('/') }}" entity_id="{{ $user->id }}" school_id="{{ $user->school_id }}" name="{{ $user->name }}"></profile-tab-parent>

                    <div id="parent-profile"></div>
                </div>

                @livewire('custom-fields.show-all-custom-fields', [
                    'entityType' => 'parent',
                    'entityId' => $user->id,
                    'schoolId' => $user->school_id,
                ])
            </div>
        </div>
    </div>

@endsection

@push('scripts')

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.activate').on('click', function(){
            var link = $(this).attr('rel');
            var status = $(this).attr('value');
            //alert(status);
            swal({
                icon: "info",
                text: "Do you want to change the status ?",
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
                        type: "POST",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success:function(data)
                        {
                            //alert(ans);
                            swal({
                                icon: "success",
                                text: "Parent Status Updated Successfully",
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


    $(document).ready(function(){
        $('.reset').on('click', function(){
            var link = $(this).attr('rel');
            //alert(link);
            swal({
                icon: "info",
                text: "Do you want to reset password for this parent ?",
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
                                text: "Check your email to reset the password",
                                showConfirmButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
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

    $(document).ready(function(){
        $('.verify').on('click', function(){
            var link = $(this).attr('rel');
            //alert(link);
            swal({
                icon: "info",
                text: "Do you want to verify email for this parent ?",
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
                                text: "Verification code sent Successfully",
                                showConfirmButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
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

    $(document).ready(function(){
        $('.deleteuser').on('click', function(){
            var link = $(this).attr('rel');
            var status = $(this).attr('value');
            //alert(status);
            swal({
                icon: "info",
                text: "Do you want to change the Delete Parent ?",
                buttons: {
                    cancel: true,
                    confirm: true,
                },
                allowOutsideClick: false,
            }).then((willChange) => {
                if (willChange) 
                {
                    $( "#parent_delete" ).submit();
                } 
                else 
                {
                    swal("Cancelled");
                } 
            });
        });
    });


</script>

@endpush