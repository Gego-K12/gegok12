@extends('layouts.admin.layout')
@section('content')
<h1 class="admin-h1 my-4">Academy test accounts</h1>
<p class="my-3">One administrator, teacher, student and parent are prepared as a connected test group. Existing accounts and data are retained. Ask the administrator for the password issued when the accounts were prepared.</p>
<div class="bg-white rounded p-4"><table class="w-full text-sm"><thead><tr class="text-left"><th class="p-2">Role</th><th class="p-2">Email</th><th class="p-2">Status</th></tr></thead><tbody>
@foreach([3 => 'Administrator', 5 => 'Teacher', 6 => 'Student', 7 => 'Parent'] as $role => $label)<tr class="border-b"><td class="p-2">{{ $label }}</td><td class="p-2">{{ config('academy_backend.demo_emails.'.$role) }}</td><td class="p-2">{{ $accounts->has($role) ? 'Prepared' : 'Not prepared yet' }}</td></tr>@endforeach
</tbody></table></div>
@endsection
