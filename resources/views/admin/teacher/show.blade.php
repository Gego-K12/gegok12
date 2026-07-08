@extends('layouts.admin.layout')

@section('content')
    <div>
        @include('partials.message')

        <div class="py-2 mt-2 flex items-center">
            <a href="{{ url('/admin/teachers') }}" class="rounded-full bg-gray-100 hover:bg-gray-200 transition p-2" title="Back">
                <svg class="w-3 h-3 fill-current text-gray-700" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" style="enable-background:new 0 0 492 492;" xml:space="preserve" width="512px" height="512px"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124 c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844 L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412 c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008 c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788 C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" class="active-path" fill=""/></g></g></g> </svg>
            </a>
            <h1 class="admin-h1 mx-3">Teaching Staff Profile</h1>
        </div>

        {{-- Row 1: head summary card --}}
        <div class="bg-white rounded-lg shadow border border-gray-100 mb-5">
            {{-- Sub-row 1: name, status, id, designation | actions --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 border-b border-gray-100">
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <h2 class="text-2xl font-bold text-gray-800">{{ ucwords($user->FullName) }}</h2>
                        @if(optional($user->userprofile)->status == 'exit')
                            <span class="rounded-full px-2 py-1 text-xs font-semibold bg-red-100 text-red-700">Relieved</span>
                        @elseif(optional($user)->status == 'inactive')
                            <span class="rounded-full px-2 py-1 text-xs font-semibold bg-gray-200 text-gray-600">Inactive</span>
                        @else
                            <span class="rounded-full px-2 py-1 text-xs font-semibold bg-green-100 text-green-700">Active</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 mt-1">{{ $user->getTeacherDetails()['designation_name'] ?? '' }} &middot; ID: {{ $user->id }}</p>
                </div>

                <div class="flex flex-wrap items-center gap-2 flex-shrink-0">
                    <a href="{{url('/admin/teacher/edit/'.$user->name)}}" title="Edit" class="rounded-full bg-gray-100 hover:bg-gray-200 transition p-2" id="edit">
                        <svg class="w-4 h-4 fill-current text-gray-700" id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g><path d="m128.285 260.925h319.073v75h-319.073z" transform="matrix(.707 -.707 .707 .707 -126.717 290.929)"/><path d="m29.021 422.521-29.021 89.479 89.481-29.02z"/><path d="m54.039 186.679h319.073v75h-319.073z" transform="matrix(.707 -.707 .707 .707 -95.964 216.682)"/><path d="m371.541 5.46h90v180h-90z" transform="matrix(.707 -.707 .707 .707 54.502 322.498)"/><path d="m57.148 335.796-17.737 54.689 82.106 82.105 54.689-17.737z"/></g></svg>
                    </a>

                    <div class="relative">
                        <button type="button" class="more-actions-toggle rounded-full bg-gray-100 hover:bg-gray-200 transition p-2 flex items-center justify-center" title="More actions">
                            <svg class="w-4 h-4 fill-current text-gray-700" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="5" cy="12" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="19" cy="12" r="2"/></svg>
                        </button>
                        <div class="more-actions-menu hidden absolute right-0 mt-1 w-64 bg-white rounded shadow-lg border border-gray-100 py-1 z-10 text-xs">
                            @if(optional($user)->status == "inactive" || optional($user->userprofile)->status == "exit")
                                <a href="#" rel="{{ url('/admin/user/updateStatus/'.$user->name) }}" class="activate block px-4 py-2 text-green-600 hover:bg-gray-50" value="active" id="status">Activate</a>
                            @else
                                <a href="#" rel="{{ url('/admin/user/updateStatus/'.$user->name) }}" class="activate block px-4 py-2 text-red-600 hover:bg-gray-50" value="inactive" id="status">Deactivate</a>
                            @endif

                            @if(optional($user->userprofile)->status != "exit")
                                <a href="#" rel="{{ url('/admin/user/updateStatus/'.$user->name) }} " class="activate block px-4 py-2 text-teal-600 hover:bg-gray-50" value="exit" id="status">Relieve</a>
                            @endif

                            <a href="#" class="open-credentials block px-4 py-2 text-gray-700 hover:bg-gray-50">Change Credentials</a>

                            <div class="border-t my-1"></div>

                            @if($user->email != null && optional($user->userprofile)->status != "exit")
                                @if($user->email_verified == 1)
                                    <a href="#" rel="{{ url('/admin/user/resetPassword/'.$user->id) }}" class="reset block px-4 py-2 text-gray-700 hover:bg-gray-50">Reset Password</a>
                                @endif
                                @if($user->email_verified != 1)
                                    <a href="#" rel="{{ url('/admin/user/'.$user->id.'/verificationcode') }}" class="verify block px-4 py-2 text-gray-700 hover:bg-gray-50" id="verify_mail">Verify Email</a>
                                @endif
                            @endif

                            @if($user->userprofile->usergroup_id=='8')
                                <a href="{{ url('/library/'.$user->id.'/impersonate') }}" target="_blank" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Login as Librarian</a>
                            @else
                                <a href="{{ url('/teacher/'.$user->id.'/impersonate') }}" target="_blank" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Login as Teacher</a>
                            @endif

                            <a href="{{url('/admin/teacher/id-card/'.$user->name)}}" title="ID CARD" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Id Card</a>

                            <div class="border-t my-1"></div>

                            <form action="{{ url('/admin/teacher/delete', ['name'=>$user->name]) }}" method="POST" id="delete">
                                @csrf
                                @method('delete')
                                <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sub-row 2: avatar + info grid --}}
            <div class="flex flex-col sm:flex-row gap-5 p-6">
                <img src="{{ $user->userprofile->AvatarPath }}" class="w-24 h-24 rounded-full object-cover ring-4 ring-gray-50 flex-shrink-0 mx-auto sm:mx-0 sm:mr-6">

                <div class="ml-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-3 text-xs w-full">
                    <div>
                        <p class="uppercase text-gray-400" style="font-size:10px;">Employee ID</p>
                        <p class="font-semibold text-gray-700">{{ $user->getTeacherDetails()['employee_id'] ?? '--' }}</p>
                    </div>
                    <div>
                        <p class="uppercase text-gray-400" style="font-size:10px;">Mobile</p>
                        <p class="font-semibold text-gray-700">{{ $user->mobile_no ?? '--' }}</p>
                    </div>
                    <div>
                        <p class="uppercase text-gray-400" style="font-size:10px;">Email</p>
                        <p class="font-semibold text-gray-700 truncate">{{ $user->email ?: '--' }}</p>
                    </div>
                    <div>
                        <p class="uppercase text-gray-400" style="font-size:10px;">Gender</p>
                        <p class="font-semibold text-gray-700 capitalize">{{ optional($user->userprofile)->gender ?? '--' }}</p>
                    </div>
                    <div>
                        <p class="uppercase text-gray-400" style="font-size:10px;">Date of Birth</p>
                        <p class="font-semibold text-gray-700">{{ optional($user->userprofile)->date_of_birth ? date('d-m-Y', strtotime($user->userprofile->date_of_birth)) : '--' }}</p>
                    </div>
                    <div>
                        <p class="uppercase text-gray-400" style="font-size:10px;">Blood Group</p>
                        <p class="font-semibold text-gray-700 uppercase">{{ optional($user->userprofile)->blood_group ?? '--' }}</p>
                    </div>
                    <div>
                        <p class="uppercase text-gray-400" style="font-size:10px;">Joining Date</p>
                        <p class="font-semibold text-gray-700">{{ optional($user->userprofile)->joining_date ? date('d-m-Y', strtotime($user->userprofile->joining_date)) : '--' }}</p>
                    </div>
                    @if(optional($user->userprofile)->status == 'exit' && optional($user->userprofile)->relieved_at)
                        <div>
                            <p class="uppercase text-gray-400" style="font-size:10px;">Relieved On</p>
                            <p class="font-semibold text-gray-700">{{ date('d-m-Y', strtotime($user->userprofile->relieved_at)) }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="uppercase text-gray-400" style="font-size:10px;">Aadhaar Number</p>
                        <p class="font-semibold text-gray-700">{{ optional($user->userprofile)->aadhar_number ?: '--' }}</p>
                    </div>
                    <div class="col-span-2 sm:col-span-3 lg:col-span-4">
                        <p class="uppercase text-gray-400" style="font-size:10px;">Address</p>
                        <p class="font-semibold text-gray-700">{{ optional($user->userprofile)->address ?: '--' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <change-credential url="{{url('/')}}" name="{{$user->name}}" hide-button="true"></change-credential>

        {{-- Row 2: tabbed detail view --}}
        <div class="bg-white rounded-lg shadow border border-gray-100">
            <profile-tab-teacher url="{{url('/')}}" entity_id="{{ $user->id }}" school_id="{{ $user->school_id }}" name="{{$user->name}}" mode="teacher"></profile-tab-teacher>
            <div id="teacherprofile" class="p-4"></div>
        </div>

        @livewire('admin.profile-extra-tabs', ['entityId' => $user->id, 'scope' => 'teacher'])

        {{-- Deactivate confirmation modal --}}
        <div id="deactivate-confirm-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Deactivate this staff member?</h2>
                <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                    Deactivating <strong>{{ ucwords($user->FullName) }}</strong> will immediately block them from logging in.
                    Their historical records &mdash; attendance, marks, leave applications, assignments &mdash; will stay
                    intact, and you can reactivate this account at any time from the same menu.
                </p>
                <label class="flex items-start gap-2 text-sm text-gray-700 mb-6 cursor-pointer">
                    <input type="checkbox" id="deactivate-ack" class="mt-1">
                    <span>I understand this will prevent {{ ucwords($user->FullName) }} from logging in.</span>
                </label>
                <div class="flex justify-end gap-2">
                    <button type="button" id="deactivate-cancel-btn" class="px-4 py-2 rounded text-sm font-medium text-gray-600 hover:bg-gray-100">Cancel</button>
                    <button type="button" id="deactivate-confirm-btn" disabled class="px-4 py-2 rounded text-sm font-medium text-white bg-red-600 opacity-40 cursor-not-allowed">Deactivate</button>
                </div>
            </div>
        </div>

        {{-- Activate confirmation modal --}}
        <div id="activate-confirm-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Activate this staff member?</h2>
                <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                    Activating <strong>{{ ucwords($user->FullName) }}</strong> will immediately restore their ability to
                    log in and access the system. Make sure this is intentional before proceeding.
                </p>
                <label class="flex items-start gap-2 text-sm text-gray-700 mb-6 cursor-pointer">
                    <input type="checkbox" id="activate-ack" class="mt-1">
                    <span>I understand this will allow {{ ucwords($user->FullName) }} to log in again.</span>
                </label>
                <div class="flex justify-end gap-2">
                    <button type="button" id="activate-cancel-btn" class="px-4 py-2 rounded text-sm font-medium text-gray-600 hover:bg-gray-100">Cancel</button>
                    <button type="button" id="activate-confirm-btn" disabled class="px-4 py-2 rounded text-sm font-medium text-white custom-green opacity-40 cursor-not-allowed">Activate</button>
                </div>
            </div>
        </div>

        {{-- Relieve confirmation modal --}}
        <div id="relieve-confirm-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-red-600 fill-current flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2 1 21h22L12 2zm0 5.5 6.9 11.5H5.1L12 7.5zM11 10v4h2v-4h-2zm0 5v2h2v-2h-2z"/></svg>
                    <h2 class="text-lg font-semibold text-gray-800">Relieve {{ ucwords($user->FullName) }}?</h2>
                </div>
                <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                    This marks <strong>{{ ucwords($user->FullName) }}</strong> as having permanently left the school.
                    It is meant for staff who have genuinely exited &mdash; not for a temporary absence, which should be
                    handled with <strong>Deactivate</strong> instead. This is a critical action, so please confirm each
                    point below.
                </p>
                <div class="space-y-3 mb-6">
                    <label class="flex items-start gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" class="relieve-ack mt-1" id="relieve-ack-1">
                        <span>{{ ucwords($user->FullName) }} has actually left the school and will not be returning under this record.</span>
                    </label>
                    <label class="flex items-start gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" class="relieve-ack mt-1" id="relieve-ack-2">
                        <span>I understand this will immediately and permanently block their login, and their class teacher / subject assignments should be reassigned to someone else.</span>
                    </label>
                    <label class="flex items-start gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" class="relieve-ack mt-1" id="relieve-ack-3">
                        <span>I understand their historical records (attendance, marks, remarks, homework, assignments) will be preserved, not deleted.</span>
                    </label>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" id="relieve-cancel-btn" class="px-4 py-2 rounded text-sm font-medium text-gray-600 hover:bg-gray-100">Cancel</button>
                    <button type="button" id="relieve-confirm-btn" disabled class="px-4 py-2 rounded text-sm font-medium text-white bg-red-600 opacity-40 cursor-not-allowed">Relieve</button>
                </div>
            </div>
        </div>

        {{-- Reset password confirmation modal --}}
        <div id="reset-password-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-blue-600 fill-current flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 17a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm6-9h-1V6a5 5 0 0 0-10 0v2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zM9 6a3 3 0 0 1 6 0v2H9V6zm9 14H6V10h12v10z"/></svg>
                    <h2 class="text-lg font-semibold text-gray-800">Send password reset link?</h2>
                </div>
                <p class="text-sm text-gray-600 mb-2 leading-relaxed">
                    We'll email a password reset link to:
                </p>
                <p class="text-sm font-semibold text-gray-800 bg-gray-50 border border-gray-100 rounded px-3 py-2 mb-4">
                    {{ $user->email }}
                </p>
                <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                    {{ ucwords($user->FullName) }}'s current password keeps working until they open the link and set a
                    new one &mdash; this doesn't lock them out.
                </p>
                <div class="flex justify-end gap-2">
                    <button type="button" id="reset-password-cancel-btn" class="px-4 py-2 rounded text-sm font-medium text-gray-600 hover:bg-gray-100">Cancel</button>
                    <button type="button" id="reset-password-confirm-btn" class="px-4 py-2 rounded text-sm font-medium text-white blue-bg">Send Reset Link</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.more-actions-toggle').on('click', function(e){
            e.stopPropagation();
            $('.more-actions-menu').toggleClass('hidden');
        });

        $(document).on('click', function(){
            $('.more-actions-menu').addClass('hidden');
        });

        $('.open-credentials').on('click', function(e){
            e.preventDefault();
            window.bus.emit('openChangeCredentials');
        });
    });

    $(document).ready(function(){
        var deactivateLink = null;
        var activateLink = null;
        var relieveLink = null;

        function updateStatus(link, status)
        {
            $.ajax({
                url: link,
                data: { status: status },
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(data)
                {
                    swal({
                        icon: "success",
                        text: "Teacher Status Updated Successfully",
                    }).then(function(){
                        window.location.reload();
                    });
                }
            });
        }

        $('.activate').on('click', function(){
            var link = $(this).attr('rel');
            var status = $(this).attr('value');

            if (status === 'inactive')
            {
                deactivateLink = link;
                $('#deactivate-ack').prop('checked', false);
                $('#deactivate-confirm-btn').prop('disabled', true).addClass('opacity-40 cursor-not-allowed');
                $('#deactivate-confirm-modal').removeClass('hidden');
                return;
            }

            if (status === 'active')
            {
                activateLink = link;
                $('#activate-ack').prop('checked', false);
                $('#activate-confirm-btn').prop('disabled', true).addClass('opacity-40 cursor-not-allowed');
                $('#activate-confirm-modal').removeClass('hidden');
                return;
            }

            if (status === 'exit')
            {
                relieveLink = link;
                $('.relieve-ack').prop('checked', false);
                $('#relieve-confirm-btn').prop('disabled', true).addClass('opacity-40 cursor-not-allowed');
                $('#relieve-confirm-modal').removeClass('hidden');
                return;
            }

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
                    updateStatus(link, status);
                }
                else
                {
                    swal("Cancelled");
                }
            });
        });

        $('#deactivate-ack').on('change', function(){
            var checked = $(this).is(':checked');
            $('#deactivate-confirm-btn').prop('disabled', !checked).toggleClass('opacity-40 cursor-not-allowed', !checked);
        });

        $('#deactivate-cancel-btn').on('click', function(){
            $('#deactivate-confirm-modal').addClass('hidden');
        });

        $('#deactivate-confirm-modal').on('click', function(e){
            if (e.target === this)
            {
                $(this).addClass('hidden');
            }
        });

        $('#deactivate-confirm-btn').on('click', function(){
            if ($(this).prop('disabled') || deactivateLink === null)
            {
                return;
            }
            $('#deactivate-confirm-modal').addClass('hidden');
            updateStatus(deactivateLink, 'inactive');
        });

        $('#activate-ack').on('change', function(){
            var checked = $(this).is(':checked');
            $('#activate-confirm-btn').prop('disabled', !checked).toggleClass('opacity-40 cursor-not-allowed', !checked);
        });

        $('#activate-cancel-btn').on('click', function(){
            $('#activate-confirm-modal').addClass('hidden');
        });

        $('#activate-confirm-modal').on('click', function(e){
            if (e.target === this)
            {
                $(this).addClass('hidden');
            }
        });

        $('#activate-confirm-btn').on('click', function(){
            if ($(this).prop('disabled') || activateLink === null)
            {
                return;
            }
            $('#activate-confirm-modal').addClass('hidden');
            updateStatus(activateLink, 'active');
        });

        $('.relieve-ack').on('change', function(){
            var allChecked = $('.relieve-ack:checked').length === $('.relieve-ack').length;
            $('#relieve-confirm-btn').prop('disabled', !allChecked).toggleClass('opacity-40 cursor-not-allowed', !allChecked);
        });

        $('#relieve-cancel-btn').on('click', function(){
            $('#relieve-confirm-modal').addClass('hidden');
        });

        $('#relieve-confirm-modal').on('click', function(e){
            if (e.target === this)
            {
                $(this).addClass('hidden');
            }
        });

        $('#relieve-confirm-btn').on('click', function(){
            if ($(this).prop('disabled') || relieveLink === null)
            {
                return;
            }
            $('#relieve-confirm-modal').addClass('hidden');
            updateStatus(relieveLink, 'exit');
        });
    });


    $(document).ready(function(){
        var resetPasswordLink = null;

        $('.reset').on('click', function(){
            resetPasswordLink = $(this).attr('rel');
            $('#reset-password-modal').removeClass('hidden');
        });

        $('#reset-password-cancel-btn').on('click', function(){
            $('#reset-password-modal').addClass('hidden');
        });

        $('#reset-password-modal').on('click', function(e){
            if (e.target === this)
            {
                $(this).addClass('hidden');
            }
        });

        $('#reset-password-confirm-btn').on('click', function(){
            if (resetPasswordLink === null)
            {
                return;
            }
            $('#reset-password-modal').addClass('hidden');
            $.ajax({
                url: resetPasswordLink,
                type: "GET",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(data)
                {
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
            });
        });
    });

    $(document).ready(function(){
        $('.verify').on('click', function(){
            var link = $(this).attr('rel');
            //alert(link);
            swal({
                icon: "info",
                text: "Do you want to verify email for this teacher ?",
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
        $('.exit-member').on('click', function(){
            var link = $(this).attr('rel');
            var name = {!! json_encode($user->name) !!};
            //alert(link);
            swal({
                icon: "info",
                text: "Do you want to exit this member ?",
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
                            //alert(name);
                            window.location.href="/admin/teacher/exit/"+name;
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

@endpush
