<!DOCTYPE html>
<html>
<head>
   <title>Certificate</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans">
   <div class="relative">

        <div class="w-full  mx-auto bg-white certificate-banner mt-5" style="background-image: url(uploads/certificate-banner.jpg);
        background-size: cover;
        background-position: center;">
            <div class="" style="padding: 20px;">
                 <div style="padding-top: 30px;">
                <div class="" style="color: #3F056F;text-align: center;padding:top:12px;padding-bottom: 12px; text-transform: uppercase;font-weight: bold;font-size: 25px;margin-bottom: 0;">
                 {{ ucwords(Auth::user()->school->name) }} MATRIC HR.SEC. SCHOOL MATRIC HR. SEC. SCHOOL,</div>
                <div class="font-libre" style="color: #00518E;text-align: center;padding-top: 8px;font-weight: 500;margin-bottom: 0;font-size: 24px;">{{ ucfirst($certificate->program_name) }} - {{ $certificate->date ? \Carbon\Carbon::parse($certificate->date)->format('Y') :'' }}</div>
                <div class="algerian-font" style="color: #CC0622;text-align: center;text-transform: uppercase;padding-top: 12px;font-size: 18px;">certificate of Achivement</div>
            </div>
                <div style="padding-top:30px;padding: 50px 0;width: 100%;">
                   <!--  @php 
                    if($user->avatar == '')
                    {
                        $image='images/user.jpeg';
                    }
                    else
                    {
                        $image=$user->avatar;
                    }

                    @endphp -->
                    <!-- <div class="mb-3"><img src="{{url($image)}}" class="w-32 h-32 mx-auto"></div> -->

                    <table style="width: 100%;">
                        <tbody style="text-align: center;">
                        <tr>
                            <td style="text-align: center;" >
                             
                             <div style="min-width: 100px;min-height: 100px;max-width:120px;max-height:120px;width:120px;height:120px;margin-left:auto;margin-right: auto;"> 
                                <img src="{{$user->AvatarPath}}" style="width: 100%;height: 100%;">
                             </div>
                             
                          </td>
                        </tr>
                        <tr>
                            <td>

                             <div class="mono-font" style="margin-top: 20px 0;padding-top: 5px;text-align: center;font-weight: 700;color: #000;font-size: 16px;line-height: 1.5;">This certificate is awarded to honour <span class="font-bold" style="color: #00B0F0;">{{$user->firstname}} {{$user->lastname}} </span> of <span class="font-bold">{{$certificate->standard}} Std</span> for {{ ($user->gender=='male'?'his':'her')}} remarkable achievement of the <span class="font-bold" style="color: #7D010D;">{{$certificate->certificate_for}}</span> in the {{$certificate->event_name}}  on {{ $certificate->date ? \Carbon\Carbon::parse($certificate->date)->format('d') : '' }}{{ $certificate->date ? \Carbon\Carbon::parse($certificate->date)->format('F') : '' }} {{ $certificate->date ? \Carbon\Carbon::parse($certificate->date)->format('Y') : '' }}.</div>


                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="pb-5" style="text-align: right;padding-top: 30px;">
                <div style="font-size: 15px;">
                    <p style="font-weight: 900;">Principal</p>
                </div>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                    
                
                 {{-- <div class="text-lg lg:text-2xl md:text-2xl font-semibold text-center pt-1 mb-4 mt-4 pt-4 text-black mono-font w-full lg:w-10/12 md:w-10/12 mx-auto">This certificate is awarded to honour <span class="font-bold" style="color: #00B0F0;">Mr. {{$user->firstname}} </span> of <span class="font-bold">X Std</span> for her remarkable achievement of the <span class="font-bold" style="color: #7D010D;">{{$certificate->certificate_for}}</span> in the {{$certificate->event_name}}  on {{$certificate->date->format('d ')}}{{$certificate->date->format('F ')}} {{$certificate->date->format('Y')}}.</div> --}}
            </div>
            </div>
           {{--  <div class="flex items-start justify-end px-12 pb-5">
                <div class=" text-xl">
                    <p class="font-extrabold ">Principal</p>
                </div>
            </div> --}}
        </div>

</body>
</html>
<style>
.page-break {
 page-break-after: always;
}
h1,h2,h3,h4,h5,h6,p {
 margin: unset !important;
}



</style>