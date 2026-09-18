<!DOCTYPE html>
<html>
<head>
    <title>{{ $user->FullName }} - Student Profile</title>
</head>
<body style="font-family: sans-serif; font-size: 12px; color: #333;">

    <table style="width: 100%; margin-bottom: 15px;">
        <tr>
            <td style="text-align: left;">
                <h2 style="margin: 0; font-size: 18px;">{{ optional(Auth::user()->school)->name }}</h2>
                <p style="margin: 2px 0 0 0; color: #718096;">Student Profile</p>
            </td>
            <td style="text-align: right; vertical-align: top;">
                <p style="margin: 0;">Generated on: {{ date('d-m-Y') }}</p>
            </td>
        </tr>
    </table>
    <hr style="border: none; border-top: 2px solid #2d3748; margin-bottom: 15px;">

    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td style="width: 70%; vertical-align: top;">
                <h1 style="margin: 0; font-size: 22px;">{{ ucwords($user->FullName) }}</h1>
                <p style="margin: 4px 0 0 0; color: #718096;">ID: {{ $user->id }}</p>
            </td>
            @if($avatarData != null)
            <td style="width: 30%; text-align: right; vertical-align: top;">
                <img src="{{ $avatarData }}" style="width: 80px; height: 80px; border-radius: 6px;">
            </td>
            @endif
        </tr>
    </table>

    <h3 style="background-color: #edf2f7; padding: 6px 10px; margin-bottom: 0;">Basic Information</h3>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
        <tr>
            <td style="width: 25%; padding: 6px 10px; border-bottom: 1px solid #e2e8f0; color: #718096;">Date Of Birth</td>
            <td style="width: 25%; padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ date('d-m-Y',strtotime(optional($user->userprofile)->date_of_birth)) }}</td>
            <td style="width: 25%; padding: 6px 10px; border-bottom: 1px solid #e2e8f0; color: #718096;">Blood Group</td>
            <td style="width: 25%; padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ strtoupper(optional($user->userprofile)->blood_group) }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0; color: #718096;">Gender</td>
            <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">
                @if(optional($user->userprofile)->gender == 'male') Boy
                @elseif(optional($user->userprofile)->gender == 'female') Girl
                @endif
            </td>
            <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0; color: #718096;">Aadhaar Number</td>
            <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ optional($user->userprofile)->aadhar_number != '' ? optional($user->userprofile)->aadhar_number : '--' }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0; color: #718096;">Birth Place</td>
            <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ optional($user->userprofile)->birth_place }}</td>
            <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0; color: #718096;">Native Place</td>
            <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ optional($user->userprofile)->native_place }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 10px; color: #718096;">Mother Tongue</td>
            <td style="padding: 6px 10px;">{{ optional($user->userprofile)->mother_tongue }}</td>
            <td style="padding: 6px 10px; color: #718096;">Caste</td>
            <td style="padding: 6px 10px;">{{ optional($user->userprofile)->caste }}</td>
        </tr>
    </table>

    <h3 style="background-color: #edf2f7; padding: 6px 10px; margin-bottom: 0;">Contact Information</h3>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
        <tr>
            <td style="width: 25%; padding: 6px 10px; border-bottom: 1px solid #e2e8f0; color: #718096;">Address</td>
            <td style="width: 75%; padding: 6px 10px; border-bottom: 1px solid #e2e8f0;" colspan="3">{{ optional($user->userprofile)->address != null ? optional($user->userprofile)->address : '--' }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 10px; color: #718096;">Mobile</td>
            <td style="padding: 6px 10px;">{{ $user->mobile_no != null ? $user->mobile_no : '--' }}</td>
            <td style="padding: 6px 10px; color: #718096;">Email</td>
            <td style="padding: 6px 10px;">{{ $user->email != null ? $user->email : '--' }}</td>
        </tr>
    </table>

    <h3 style="background-color: #edf2f7; padding: 6px 10px; margin-bottom: 0;">Academic Details</h3>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
        <tr>
            <td style="width: 25%; padding: 6px 10px; border-bottom: 1px solid #e2e8f0; color: #718096;">Age</td>
            <td style="width: 25%; padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ date('Y')-date('Y',strtotime(optional($user->userprofile)->date_of_birth)) }}</td>
            <td style="width: 25%; padding: 6px 10px; border-bottom: 1px solid #e2e8f0; color: #718096;">Admission Number</td>
            <td style="width: 25%; padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ $user->registration_number == null ? optional($user->userprofile)->registration_number : $user->registration_number }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0; color: #718096;">EMIS Number</td>
            <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ optional($user->userprofile)->EMIS_number }}</td>
            <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0; color: #718096;">Joining Date</td>
            <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ optional($user->userprofile)->joining_date != null ? date('d-m-Y',strtotime($user->userprofile->joining_date)) : '--' }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0; color: #718096;">Class</td>
            <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ optional(optional($user->studentAcademicLatest)->standardLink)->StandardSection }}</td>
            <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0; color: #718096;">Roll Number</td>
            <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ optional($user->studentAcademicLatest)->roll_number }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0; color: #718096;">ID Card Number</td>
            <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ optional($user->studentAcademicLatest)->id_card_number }}</td>
            <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0; color: #718096;">Library Card Number</td>
            <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ optional($user->librarycard)->library_card_no }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 10px; color: #718096;">Transport</td>
            <td style="padding: 6px 10px;" colspan="3">{{ ucwords(str_replace('_', ' ', optional($user->studentAcademicLatest)->mode_of_transport)) }}</td>
        </tr>
    </table>

    <h3 style="background-color: #edf2f7; padding: 6px 10px; margin-bottom: 0;">Attendance</h3>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
        <tr>
            <td style="width: 25%; padding: 6px 10px; border-bottom: 1px solid #e2e8f0; color: #718096;">Total Sessions</td>
            <td style="width: 25%; padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ $attendanceTotal }}</td>
            <td style="width: 25%; padding: 6px 10px; border-bottom: 1px solid #e2e8f0; color: #718096;">Present</td>
            <td style="width: 25%; padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ $attendancePresent }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 10px; color: #718096;">Absent</td>
            <td style="padding: 6px 10px;">{{ $attendanceAbsent }}</td>
            <td style="padding: 6px 10px; color: #718096;">Attendance %</td>
            <td style="padding: 6px 10px;">{{ $attendancePercentage }}%</td>
        </tr>
    </table>

    @if(count($feeDetails) > 0)
    <h3 style="background-color: #edf2f7; padding: 6px 10px; margin-bottom: 0;">Fee Details</h3>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
        <thead>
            <tr style="background-color: #f7fafc;">
                <th style="text-align: left; padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">Fee Name</th>
                <th style="text-align: left; padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">Term</th>
                <th style="text-align: left; padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">Amount</th>
                <th style="text-align: left; padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">Status</th>
                <th style="text-align: left; padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">Paid On</th>
            </tr>
        </thead>
        <tbody>
            @foreach($feeDetails as $fee)
            <tr>
                <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ $fee['name'] }}</td>
                <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ $fee['term'] }}</td>
                <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ number_format($fee['amount'],2) }}</td>
                <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ $fee['status'] }}</td>
                <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ $fee['paid_on'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($user->parents->count() > 0)
    <h3 style="background-color: #edf2f7; padding: 6px 10px; margin-bottom: 0;">Parents / Guardians</h3>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f7fafc;">
                <th style="text-align: left; padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">Name</th>
                <th style="text-align: left; padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">Relation</th>
                <th style="text-align: left; padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">Mobile</th>
                <th style="text-align: left; padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user->parents as $link)
                @if($link->userParent)
                <tr>
                    <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ ucwords($link->userParent->FullName) }}</td>
                    <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ ucfirst(optional($link->userParent->getParentDetails())['relation'] ?? '--') }}</td>
                    <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ $link->userParent->mobile_no != null ? $link->userParent->mobile_no : '--' }}</td>
                    <td style="padding: 6px 10px; border-bottom: 1px solid #e2e8f0;">{{ $link->userParent->email != null ? $link->userParent->email : '--' }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    @endif

</body>
</html>
