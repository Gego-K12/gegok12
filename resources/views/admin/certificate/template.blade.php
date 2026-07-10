@extends('layouts.admin.layout')

@section('content')
           <div class="relative">
        <div class="flex flex-wrap lg:flex-row justify-between my-5">
            <h1 class="admin-h1  flex items-center"><span class="mx-3">Certificate</span> </h1>
            <div>
            <a href="{{ url('/admin/certificate/printcertificate/'.$certificate->id) }}" target="_blank" class="text-xs">
                <div class="bg-blue-600 text-white px-3 py-1 rounded">
                    Print
                </div>
     			 </a>
			  </div>
        </div>

        <div class="w-full  mx-auto bg-white certificate-banner mt-5" style="background-image: url('/uploads/certificate-banner.jpg');
		background-size: cover;
		background-position: bottom;">
        	<div class="p-5 ">

        		<h1 class="text-base lg:text-3xl md:text-3xl font-extrabold text-center pt-2 pb-2 uppercase" style="color: #3F056F;text-shadow: 2px 2px #3F056F;">{{ ucwords(Auth::user()->school->name) }} MATRIC HR.SEC. SCHOOL</h1>
        		<h2 class="text-base lg:text-3xl md:text-3xl font-medium text-center pt-2 pb-1 font-libre" style="color: #00518E;">{{ ucfirst($certificate->program_name) }} - {{ $certificate->date ? \Carbon\Carbon::parse($certificate->date)->format('Y') : '' }}</h2>
        		<h2 class="text-base lg:text-2xl md:text-2xl text-center pt-2 algerian-font uppercase" style="color: #CC0622;">certificate of Achivement</h2>
        		<div class="py-12">
        			<div class="mb-3"><img src="{{$user->AvatarPath}}" class="w-32 h-32 mx-auto"></div>
        		
        		<div class="text-lg lg:text-2xl md:text-2xl font-semibold text-center pt-1 mb-4 mt-4 pt-4 text-black mono-font w-full lg:w-10/12 md:w-10/12 mx-auto">This certificate is awarded to honour <span class="font-bold" style="color: #00B0F0;">{{$user->firstname}} {{$user->lastname}}</span> of <span class="font-bold">{{$certificate->standard}} Std</span> for {{ ($user->gender=='male'?'his':'her')}} remarkable achievement of the <span class="font-bold" style="color: #7D010D;">{{$certificate->certificate_for}}</span> in the {{$certificate->event_name}}  on {{ $certificate->date ? \Carbon\Carbon::parse($certificate->date)->format('d') : '' }}{{ $certificate->date ? \Carbon\Carbon::parse($certificate->date)->format('F') : '' }} {{ $certificate->date ? \Carbon\Carbon::parse($certificate->date)->format('Y') : '' }}.</div>
        	</div>
        	</div>
        	<div class="flex items-start justify-end px-12 pb-5">
        		<div class=" text-xl">
        			<p class="font-extrabold ">Principal</p>
        		</div>
        	</div>
        </div>
    </div>
@endsection
 @push('scripts')
 <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&display=swap" rel="stylesheet">
 <style>
     .font-libre {
        font-family: 'Libre Baskerville', serif;
     }
     @font-face {
  font-family: Algerian;
  src: url("../fonts/Algerian Regular");
}
 @font-face {
  font-family: Monotype;
  src: url("../fonts/Monotype Corsiva Font");
}
.mono-font {
 font-family: Monotype !important;
 font-style: italic;
}
.algerian-font {
 font-family: Algerian !important;
}
 </style>
<!-- <style>
	.certificate-banner {
		position: relative;
	}
	.certificate-banner:before {
		content:'';
		background-image: url('/uploads/certificate-banner.png');
		background-size: cover;
		background-position: bottom;
	}
</style>-->
@endpush 