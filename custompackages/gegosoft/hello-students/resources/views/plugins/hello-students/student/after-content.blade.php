@if(request()->is('student/hello-students*'))
    {{-- Defaults to this plugin's own page — narrow it to any page you want,
         e.g. request()->is('admin/teacher/show/*'), or remove the @if entirely
         to render on every page in this portal. --}}
    <div class="bg-white shadow px-4 py-3 my-4">
        <p class="text-sm text-gray-600">Hello Students — after-content hook.</p>
    </div>
@endif