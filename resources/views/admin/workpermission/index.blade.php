@extends('layouts.admin.layout')

@section('content')
<div class="relative">
    <div class="my-3 flex items-center justify-between">
        <h1 class="admin-h1 flex items-center">
            <span>Work Permissions</span>
        </h1>
    </div>

    <form action="{{ url('/admin/workpermissions') }}" method="GET">
        <div class="flex flex-wrap items-center mb-3">
            <select class="tw-form-control text-xs" name="status">
                <option value="">Filter By Status</option>
                <option value="pending" {{ request()->query('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request()->query('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request()->query('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="cancelled" {{ request()->query('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button value="Submit" type="submit" class="blue-bg text-sm text-white px-2 py-1 rounded mx-1">Submit</button>
        </div>
    </form>

    @include('admin.workpermission.list')
</div>
@endsection
