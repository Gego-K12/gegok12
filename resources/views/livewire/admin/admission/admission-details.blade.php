<div class="bg-white shadow px-4 py-3 my-3">
    <div class="flex flex-wrap justify-between items-center border-b pb-3 mb-3">
        <div>
            <p class="text-lg font-semibold text-gray-700">{{ $admission->application_no }}</p>
            <p class="text-xs text-gray-500">Submitted {{ $admission->created_at?->format('d M Y, H:i') }}</p>
        </div>
        <span class="capitalize text-xs font-semibold px-3 py-1 rounded
            @if($admission->application_status === 'Approved') bg-green-100 text-green-700
            @elseif($admission->application_status === 'Rejected') bg-red-100 text-red-700
            @else bg-yellow-100 text-yellow-700 @endif">
            {{ $admission->application_status }}
        </span>
    </div>

    @php
        $row = function ($label, $value) {
            return '<li class="flex py-1"><span class="text-gray-700 font-medium mx-2 w-1/3">'.$label.' :</span><p class="w-2/3">'.($value !== null && $value !== '' ? e($value) : '--').'</p></li>';
        };
    @endphp

    {{-- Standard Detail --}}
    <h2 class="text-sm font-bold text-gray-700 uppercase mt-4 mb-2">Standard Detail</h2>
    <ul class="list-reset text-xs leading-loose">
        {!! $row('Class Applied For', strtoupper(optional($admission->standard)->name ?? '') ?: null) !!}
    </ul>

    {{-- Student Detail --}}
    <h2 class="text-sm font-bold text-gray-700 uppercase mt-4 mb-2">Student Detail</h2>
    <div class="flex flex-col lg:flex-row">
        <div class="w-full lg:w-3/4">
            <ul class="list-reset text-xs leading-loose">
                {!! $row('First Name', ucfirst($admission->name)) !!}
                {!! $row('Last Name', ucfirst($admission->lastname) ?? null) !!}
                {!! $row('Date of Birth', $admission->date_of_birth ? date('d-m-Y', strtotime($admission->date_of_birth)) : null) !!}
                {!! $row('Gender', ucfirst($admission->gender)) !!}
                {!! $row('Height', $admission->height) !!}
                {!! $row('Weight', $admission->weight) !!}
                {!! $row('Birth Place', ucfirst($admission->birth_place)) !!}
                {!! $row('Nationality',  ucfirst($admission->nationality)) !!}
                {!! $row('Mother Tongue',  ucfirst($admission->mother_tongue)) !!}
                {!! $row('Identification Marks', $admission->identification_marks) !!}
                {!! $row('Blood Group', strtoupper($admission->blood_group)) !!}
                {!! $row('School Last Studied', ucfirst($admission->school_last_studied)) !!}
                {!! $row('Reason For Leaving', ucfirst($admission->reason_for_leaving)) !!}
                {!! $row('Address: Permanent', ucfirst($admission->permanent_address)) !!}
                {!! $row('Address: Communication', ucfirst($admission->address_for_communication)) !!}
                {!! $row('Sibling Studying Here', ucfirst($admission->siblings ?? '')) !!}
                @if($admission->siblings === 'yes')
                    {!! $row('Sibling Details', $admission->siblings_details) !!}
                @endif
            </ul>
        </div>
        <div class="w-full lg:w-1/4">
            @if($admission->avatar)
                <img src="{{ Storage::url($admission->avatar) }}" style="width: 150px;height: 150px;object-fit: cover;" class="my-2">
            @endif
        </div>
    </div>

    {{-- Academic Detail --}}
    <h2 class="text-sm font-bold text-gray-700 uppercase mt-4 mb-2">Academic Detail</h2>
    <ul class="list-reset text-xs leading-loose">
        {!! $row('English', $marks['english'] ?? null) !!}
        {!! $row('Tamil', $marks['tamil'] ?? null) !!}
        {!! $row('Maths', $marks['maths'] ?? null) !!}
        {!! $row('Science', $marks['science'] ?? null) !!}
        {!! $row('Social', $marks['social'] ?? null) !!}
        {!! $row('Group Selection', $admission->group_selection) !!}
        {!! $row('Board Registration Number', $admission->board_registration_number) !!}
    </ul>

    {{-- Parent Detail --}}
    <h2 class="text-sm font-bold text-gray-700 uppercase mt-4 mb-2">Father's Detail</h2>
    <div class="flex flex-col lg:flex-row">
        <div class="w-full lg:w-3/4">
            <ul class="list-reset text-xs leading-loose">
                {!! $row('Name', ucfirst($admission->father_name)) !!}
                {!! $row('Qualification', $fatherQualification) !!}
                {!! $row('Occupation', ucfirst($admission->father_occupation)) !!}
                {!! $row('Designation', ucfirst($admission->father_designation)) !!}
                {!! $row('Organization', ucfirst($admission->father_organisation)) !!}
                {!! $row('Income (P.A)', $admission->father_income) !!}
                {!! $row('Mobile Number', $admission->father_mobile_no) !!}
                {!! $row('Email ID', $admission->father_email) !!}
            </ul>
        </div>
        <div class="w-full lg:w-1/4">
            @if($admission->father_avatar)
                <img src="{{ Storage::url($admission->father_avatar) }}" style="width: 150px;height: 150px;object-fit: cover;" class="my-2">
            @endif
        </div>
    </div>

    <h2 class="text-sm font-bold text-gray-700 uppercase mt-4 mb-2">Mother's Detail</h2>
    <div class="flex flex-col lg:flex-row">
        <div class="w-full lg:w-3/4">
            <ul class="list-reset text-xs leading-loose">
                {!! $row('Name', ucfirst($admission->mother_name)) !!}
                {!! $row('Qualification', $motherQualification) !!}
                {!! $row('Occupation', ucfirst($admission->mother_occupation)) !!}
                {!! $row('Designation', ucfirst($admission->mother_designation)) !!}
                {!! $row('Organization', ucfirst($admission->mother_organisation)) !!}
                {!! $row('Income (P.A)', $admission->mother_income) !!}
                {!! $row('Mobile Number', $admission->mother_mobile_no) !!}
                {!! $row('Email ID', $admission->mother_email) !!}
            </ul>
        </div>
        <div class="w-full lg:w-1/4">
            @if($admission->mother_avatar)
                <img src="{{ Storage::url($admission->mother_avatar) }}" style="width: 150px;height: 150px;object-fit: cover;" class="my-2">
            @endif
        </div>
    </div>

    <h2 class="text-sm font-bold text-gray-700 uppercase mt-4 mb-2">Emergency Contact</h2>
    <ul class="list-reset text-xs leading-loose">
        {!! $row('Contact 1', $admission->emergency_contact_1) !!}
        {!! $row('Relationship 1', ucfirst($admission->relation_with_student_1)) !!}
        {!! $row('Contact 2', $admission->emergency_contact_2) !!}
        {!! $row('Relationship 2', ucfirst($admission->relation_with_student_2)) !!}
    </ul>

    {{-- Personal Detail --}}
    <h2 class="text-sm font-bold text-gray-700 uppercase mt-4 mb-2">Personal Detail</h2>
    <ul class="list-reset text-xs leading-loose">
        {!! $row('Medical History', ucfirst($admission->medical_history ?? '')) !!}
        @if($admission->medical_history === 'yes' && count($medicalDetails))
            {!! $row('Medical Details', implode(', ', $medicalDetails)) !!}
        @endif
        {!! $row('Extra Curricular Activities', ucfirst($admission->extra_curricular_activities ?? '')) !!}
        @if($admission->extra_curricular_activities === 'yes' && count($activities))
            {!! $row('Activities', implode(', ', $activities)) !!}
        @endif
        {!! $row('Mode of Transport', ucfirst($admission->mode_of_transport ?? '')) !!}
        @if($transportDetails)
            {!! $row("Driver's Name", $transportDetails['driver_name'] ?? null) !!}
            {!! $row("Driver's Phone Number", $transportDetails['driver_mobile_number'] ?? null) !!}
        @endif
    </ul>

    {{-- Additional Info --}}
    @if(count($customFieldValues))
        <h2 class="text-sm font-bold text-gray-700 uppercase mt-4 mb-2">Additional Info</h2>
        <ul class="list-reset text-xs leading-loose">
            @foreach($customFieldValues as $field)
                <li class="flex py-1">
                    <span class="text-gray-700 font-medium mx-2 w-1/3">{{ $field['label'] }} :</span>
                    <p class="w-2/3">
                        @if($field['is_file'] && $field['value'])
                            <a href="{{ Storage::url($field['value']) }}" target="_blank" class="text-blue-600 underline">View file</a>
                        @else
                            {{ $field['value'] ?: '--' }}
                        @endif
                    </p>
                </li>
            @endforeach
        </ul>
    @endif

    {{-- Approval details, once decided --}}
    @if(in_array($admission->application_status, ['Approved', 'Rejected']))
        <h2 class="text-sm font-bold text-gray-700 uppercase mt-4 mb-2">Decision</h2>
        <ul class="list-reset text-xs leading-loose">
            @if($sectionName)
                {!! $row('Section', $sectionName) !!}
            @endif
            @if($feeGroupName)
                {!! $row('Fee Group', $feeGroupName) !!}
            @endif
            @if($admission->payment_status)
                {!! $row('Payment Status', ucfirst($admission->payment_status)) !!}
            @endif
        </ul>
    @endif
</div>
