@if(session('successmessage'))<div role="status" class="bg-white p-3 my-3 border rounded">{{ session('successmessage') }}</div>@endif
@if($errors->any())
<div role="alert" class="bg-white p-3 my-3 border rounded text-red-700"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
