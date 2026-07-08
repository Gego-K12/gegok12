@extends('layouts.student.layout')

@section('content')
<div class="relative">
    <h1 class="admin-h1 mb-3">Short Term Courses</h1>

    <div class="bg-white shadow px-4 py-3 mb-4">
        <h2 class="font-semibold text-base text-gray-700 mb-3">Pending Invitations</h2>
        <div id="stc-student-invitations"></div>
    </div>

    <div class="bg-white shadow px-4 py-3">
        <h2 class="font-semibold text-base text-gray-700 mb-3">My Enrollments</h2>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b">
                    <th class="py-2">Course</th>
                    <th>Batch</th>
                    <th>Status</th>
                    <th>Attendance %</th>
                </tr>
            </thead>
            <tbody id="stc-student-enrollments"></tbody>
        </table>
    </div>
</div>

<script>
(function () {
    const invitationsEl = document.getElementById('stc-student-invitations');
    const enrollmentsEl = document.getElementById('stc-student-enrollments');

    function loadInvitations() {
        axios.get('/student/short-term-courses/my-invitations').then(function (res) {
            invitationsEl.innerHTML = res.data.map(function (invitation) {
                return '<div class="border rounded p-2 mb-2 text-sm flex items-center justify-between" data-id="' + invitation.id + '">' +
                    '<div>' + (invitation.batch && invitation.batch.course ? invitation.batch.course.name : '') +
                    ' — ' + (invitation.batch ? invitation.batch.name : '') + '</div>' +
                    '<div>' +
                    '<button type="button" class="stc-accept text-green-600 mr-2" data-id="' + invitation.id + '">Accept</button>' +
                    '<button type="button" class="stc-decline text-red-600" data-id="' + invitation.id + '">Decline</button>' +
                    '</div></div>';
            }).join('') || '<p class="text-sm text-gray-500">No pending invitations.</p>';
        });
    }

    function loadEnrollments() {
        axios.get('/student/short-term-courses/my-enrollments').then(function (res) {
            enrollmentsEl.innerHTML = res.data.map(function (row) {
                const e = row.enrollment;
                return '<tr>' +
                    '<td class="py-1">' + (e.batch && e.batch.course ? e.batch.course.name : '') + '</td>' +
                    '<td>' + (e.batch ? e.batch.name : '') + '</td>' +
                    '<td>' + e.status + '</td>' +
                    '<td>' + row.attendance_percentage + '%</td>' +
                    '</tr>';
            }).join('') || '<tr><td colspan="4" class="text-sm text-gray-500 py-2">No enrollments yet.</td></tr>';
        });
    }

    invitationsEl.addEventListener('click', function (e) {
        const acceptBtn = e.target.closest('.stc-accept');
        const declineBtn = e.target.closest('.stc-decline');
        const btn = acceptBtn || declineBtn;
        if (!btn) return;

        axios.post('/student/short-term-courses/invitations/' + btn.dataset.id + '/respond', {
            response: acceptBtn ? 'accepted' : 'declined',
        }).then(function () {
            loadInvitations();
            loadEnrollments();
        }).catch(function (err) {
            alert((err.response && JSON.stringify(err.response.data.errors)) || 'Failed to respond');
        });
    });

    loadInvitations();
    loadEnrollments();
})();
</script>
@endsection
