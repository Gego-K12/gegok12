@php
    // usergroup 3 (school admin) drilling into a /accountant/payroll/* page
    // sees admin's own nav/sidebar to navigate back out; usergroup 11
    // (accountant) -- and, defensively, anything else reaching this prefix,
    // since the middleware guarantees only {3, 11} do today -- sees
    // accountant's own nav/sidebar.
    $portalKey = Auth::user()->usergroup_id == 3 ? 'admin' : 'accountant';
@endphp
@include('layouts._common.layout', ['portalKey' => $portalKey])
