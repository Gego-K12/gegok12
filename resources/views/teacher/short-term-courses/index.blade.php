@extends('layouts.teacher.layout')

@section('content')
<div class="relative">
    <h1 class="admin-h1 mb-3">My Courses</h1>

    <div class="bg-white shadow px-4 py-3 mb-4">
        <h2 class="font-semibold text-base text-gray-700 mb-3">My Batches</h2>
        <div id="stc-teacher-batches"></div>
    </div>

    <div id="stc-teacher-sessions-panel" class="bg-white shadow px-4 py-3 mb-4" style="display:none">
        <h2 class="font-semibold text-base text-gray-700 mb-3">Sessions</h2>
        <div id="stc-teacher-sessions"></div>
    </div>

    <div id="stc-teacher-roster-panel" class="bg-white shadow px-4 py-3" style="display:none">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-base text-gray-700">Mark Attendance</h2>
            <button type="button" id="stc-roster-save" class="blue-bg text-sm text-white px-3 py-1 rounded">Save</button>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b">
                    <th class="py-2">Student</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Late</th>
                    <th>Excused</th>
                </tr>
            </thead>
            <tbody id="stc-roster-rows"></tbody>
        </table>
    </div>
</div>

<script>
(function () {
    const batchesEl = document.getElementById('stc-teacher-batches');
    const sessionsPanel = document.getElementById('stc-teacher-sessions-panel');
    const sessionsEl = document.getElementById('stc-teacher-sessions');
    const rosterPanel = document.getElementById('stc-teacher-roster-panel');
    const rosterRows = document.getElementById('stc-roster-rows');
    let activeSessionId = null;

    function loadBatches() {
        axios.get('/teacher/short-term-courses/my-batches').then(function (res) {
            batchesEl.innerHTML = res.data.map(function (batch) {
                return '<div class="border rounded p-2 mb-2 text-sm flex items-center justify-between">' +
                    '<div>' + batch.name + ' (' + (batch.course ? batch.course.name : '') + ')</div>' +
                    '<button type="button" class="stc-view-sessions text-blue-600 text-xs" data-id="' + batch.id + '">View sessions</button>' +
                    '</div>';
            }).join('') || '<p class="text-sm text-gray-500">No batches assigned yet.</p>';
        });
    }

    batchesEl.addEventListener('click', function (e) {
        const btn = e.target.closest('.stc-view-sessions');
        if (!btn) return;
        sessionsPanel.style.display = 'block';
        rosterPanel.style.display = 'none';
        axios.get('/teacher/short-term-courses/batches/' + btn.dataset.id + '/sessions').then(function (res) {
            sessionsEl.innerHTML = res.data.map(function (session) {
                return '<div class="border rounded p-2 mb-2 text-xs flex items-center justify-between">' +
                    '<div>Session ' + session.session_no + ' — ' + session.session_date + '</div>' +
                    '<button type="button" class="stc-mark-session text-blue-600" data-id="' + session.id + '">Mark attendance</button>' +
                    '</div>';
            }).join('') || '<p class="text-xs text-gray-500">No sessions generated yet.</p>';
        });
    });

    sessionsEl.addEventListener('click', function (e) {
        const btn = e.target.closest('.stc-mark-session');
        if (!btn) return;
        activeSessionId = btn.dataset.id;
        rosterPanel.style.display = 'block';
        axios.get('/teacher/short-term-courses/sessions/' + activeSessionId + '/roster').then(function (res) {
            rosterRows.innerHTML = res.data.map(function (row) {
                const uid = row.enrollment.user_id;
                const current = row.attendance ? row.attendance.status : 1;
                function radio(value, label) {
                    return '<td class="text-center"><input type="radio" name="mark-' + uid + '" value="' + value + '"' +
                        (current == value ? ' checked' : '') + '></td>';
                }
                return '<tr data-uid="' + uid + '">' +
                    '<td class="py-1">' + (row.student ? (row.student.FullName || row.student.name) : uid) + '</td>' +
                    radio(1, 'Present') + radio(0, 'Absent') + radio(2, 'Late') + radio(3, 'Excused') +
                    '</tr>';
            }).join('');
        });
    });

    document.getElementById('stc-roster-save').addEventListener('click', function () {
        const marks = {};
        rosterRows.querySelectorAll('tr').forEach(function (row) {
            const uid = row.dataset.uid;
            const checked = row.querySelector('input[type=radio]:checked');
            if (checked) {
                marks[uid] = parseInt(checked.value, 10);
            }
        });
        axios.post('/teacher/short-term-courses/sessions/' + activeSessionId + '/mark', { marks: marks })
            .then(function (res) { alert(res.data.marked + ' student(s) marked'); })
            .catch(function (err) { alert((err.response && JSON.stringify(err.response.data.errors || err.response.data.message)) || 'Failed'); });
    });

    loadBatches();
})();
</script>
@endsection
