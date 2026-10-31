@php
    $academyRole = (int) auth()->user()->usergroup_id;
    $academyPrefix = [3 => 'admin', 5 => 'teacher', 6 => 'student'][$academyRole] ?? null;
    $academyLinks = ['Students & progress' => '/learning/academy'];
    if (in_array($academyRole, [3, 5])) { $academyLinks['Groups & teachers'] = '/learning/academy/groups'; }
    if ($academyRole === 3) {
        if (!config('academy_backend.test_only')) { $academyLinks['Add student'] = '/learning/academy/accounts/new/student'; }
        $academyLinks += ['Teachers' => '/admin/teachers', 'Parents' => '/admin/parents', 'Teacher leave requests' => '/learning/academy/teacher/leaves', 'Teacher attendance' => '/learning/academy/teacher/attendance', 'Learning activity log' => '/learning/academy/activity', 'Birthdays' => '/admin/dashboard/birthday', 'Attendance' => '/admin/attendance/add'];
    }
    if ($academyRole === 3 && config('academy_backend.test_only')) { unset($academyLinks['Teachers'], $academyLinks['Parents']); $academyLinks['Test accounts'] = '/learning/academy/test-accounts'; }
    if ($academyPrefix) {
        $academyLinks += ['Dashboard' => '/'.$academyPrefix.'/dashboard', 'Homework' => '/'.$academyPrefix.'/homeworks', 'Messages' => '/'.$academyPrefix.'/conversations', 'Notices' => '/'.$academyPrefix.'/notices'];
    }
    if ($academyRole === 5) { $academyLinks['My tasks'] = '/teacher/tasks'; $academyLinks['Lesson plans'] = '/teacher/lessonplans'; $academyLinks['Calendar'] = '/teacher/events'; $academyLinks['My leave requests'] = '/learning/academy/teacher/leaves'; $academyLinks['My attendance'] = '/learning/academy/teacher/attendance'; $academyLinks['Change password'] = '/teacher/changepassword'; $academyLinks['Profile photo'] = '/teacher/changeavatar'; $academyLinks['Attendance & homework'] = '/learning/academy/groups'; }
@endphp
<ul class="list-reset text-sm">
@foreach($academyLinks as $label => $path)
    <li class="py-3 px-3 hover:bg-purple-900 {{ request()->getPathInfo() === $path ? 'active' : '' }}">
        <a href="{{ url($path) }}" class="flex items-center"><span class="mx-3 whitespace-no-wrap">{{ $label }}</span></a>
    </li>
@endforeach
</ul>
