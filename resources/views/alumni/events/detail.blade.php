@extends('layouts.alumni.layout')

@section('content')
    <div class="flex justify-between items-baseline">
        <div class="my-3 w-1/2">
            <h1 class=" text-black text-3xl">{{ $event->title }}</h1>
        </div>
        <div class="my-3 w-1/2 flex justify-end items-center">
            <div class="border rounded px-2 py-1 flex items-center mx-1">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 510 510" xml:space="preserve" class="w-3 h-3 fill-current text-gray-700"><g><g><g id="access-time"><path d="M255,0C114.75,0,0,114.75,0,255s114.75,255,255,255s255-114.75,255-255S395.25,0,255,0z     M255,459c-112.2,0-204-91.8-204-204S142.8,51,255,51s204,91.8,204,204S367.2,459,255,459z" data-original="#000000" data-old_color="fill-opacity:0.9" fill="" class="active-path"></path><polygon points="267.75,127.5 229.5,127.5 229.5,280.5 362.1,362.1 382.5,328.95 267.75,260.1" data-original="#000000" data-old_color="fill-opacity:0.9" fill="" class="active-path"></polygon></g></g></g></svg>
                <span class="text-gray-600 text-xs ml-2">{{ $duration }} Min</span>
            </div>
        </div>
    </div>
    <div class="my-1">
        <div class="bg-white shadow px-3 py-3 border">
            <ul class="list-reset leading-loose">
                <li class="flex items-center py-1">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512.001 512.001" xml:space="preserve" class="w-5 h-5 fill-current text-gray-700"><g><g><path d="M481.999,273.586v-47.58c0-8.284-6.716-15-15-15c-30.988,0-59.878,2.615-87.173,7.955 c-15.911-15.365-35.308-26.513-56.313-32.606c22.904-19.277,37.486-48.14,37.486-80.349c0-58.449-47.103-106-105-106 c-57.897,0-105,47.551-105,106c0,32.209,14.582,61.072,37.487,80.348c-21.005,6.094-40.402,17.242-56.313,32.606 c-27.295-5.339-56.185-7.955-87.173-7.955c-8.284,0-15,6.716-15,15v47.58c-17.459,6.192-30,22.865-30,42.42v30 c0,19.555,12.541,36.228,30,42.42v47.58c0,8.284,6.716,15,15,15c78.429,0,142.832,18.583,202.68,58.481 c5.015,3.342,11.621,3.35,16.641,0c59.848-39.898,124.25-58.481,202.68-58.481c8.284,0,15-6.716,15-15v-47.58 c17.459-6.192,30-22.865,30-42.42v-30C511.999,296.451,499.458,279.778,481.999,273.586z M180.999,106.006 c0-41.907,33.645-76,75-76s75,34.093,75,76c0,41.355-33.645,75-75,75C214.644,181.006,180.999,147.361,180.999,106.006z M44.999,361.006c-8.271,0-15-6.729-15-15v-30c0-8.271,6.729-15,15-15s15,6.729,15,15v30 C59.999,354.277,53.27,361.006,44.999,361.006z M240.999,470.091c-54.453-31.141-112.886-46.88-181-48.869v-32.796 c17.459-6.192,30-22.865,30-42.42v-30c0-19.555-12.541-36.228-30-42.42v-32.368c70.481,2.023,127.134,18.62,181,52.916V470.091z M255.999,268.145c-27.686-17.469-56.504-30.77-87.268-40.117c16.904-10.986,36.803-17.022,57.268-17.022h60 c20.465,0,40.364,6.036,57.268,17.022C312.503,237.375,283.684,250.676,255.999,268.145z M451.999,421.222 c-68.113,1.989-126.548,17.732-181,48.871V294.146c53.867-34.299,110.516-50.906,181-52.928v32.368 c-17.459,6.192-30,22.865-30,42.42v30c0,19.555,12.541,36.228,30,42.42V421.222z M481.999,346.006c0,8.271-6.729,15-15,15 s-15-6.729-15-15v-30c0-8.271,6.729-15,15-15s15,6.729,15,15V346.006z"></path></g></g></svg>
                    <span class="text-gray-600 mx-3">Subject :{{ $subject_name }}</span>
                </li>
                <li class="flex items-center py-1">
                    <svg class="w-5 h-5 fill-current text-gray-700" height="512pt" viewBox="0 -52 512.00001 512" width="512pt" xmlns="http://www.w3.org/2000/svg"><path d="m0 113.292969h113.292969v-113.292969h-113.292969zm30.003906-83.289063h53.289063v53.289063h-53.289063zm0 0"/><path d="m149.296875 0v113.292969h362.703125v-113.292969zm332.699219 83.292969h-302.695313v-53.289063h302.695313zm0 0"/><path d="m0 260.300781h113.292969v-113.292969h-113.292969zm30.003906-83.292969h53.289063v53.289063h-53.289063zm0 0"/><path d="m149.296875 260.300781h362.703125v-113.292969h-362.703125zm30.003906-83.292969h302.695313v53.289063h-302.695313zm0 0"/><path d="m0 407.308594h113.292969v-113.296875h-113.292969zm30.003906-83.292969h53.289063v53.289063h-53.289063zm0 0"/><path d="m149.296875 407.308594h362.703125v-113.296875h-362.703125zm30.003906-83.292969h302.695313v53.289063h-302.695313zm0 0"/></svg>
                    <span class="text-gray-600 mx-3">Event Category : {{ $event->category }}</span>
                </li>
                <li class="flex items-center py-1">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 510 510" xml:space="preserve" class="w-5 h-5 fill-current text-gray-700"><g><g><g id="access-time"><path d="M255,0C114.75,0,0,114.75,0,255s114.75,255,255,255s255-114.75,255-255S395.25,0,255,0z     M255,459c-112.2,0-204-91.8-204-204S142.8,51,255,51s204,91.8,204,204S367.2,459,255,459z" data-original="#000000" data-old_color="fill-opacity:0.9" fill="" class="active-path"></path><polygon points="267.75,127.5 229.5,127.5 229.5,280.5 362.1,362.1 382.5,328.95 267.75,260.1   " data-original="#000000" data-old_color="fill-opacity:0.9" fill="" class="active-path"></polygon></g></g></g></svg>
                    <span class="text-gray-600 mx-3">Start Date : {{ $event->start_date }} | End Date : {{ $event->end_date }}</span>
                </li>
                <li class="flex items-center py-1">
                    <svg height="682pt" viewBox="-119 -21 682 682.66669" width="682pt" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current text-gray-700"><path d="m216.210938 0c-122.664063 0-222.460938 99.796875-222.460938 222.460938 0 154.175781 222.679688 417.539062 222.679688 417.539062s222.242187-270.945312 222.242187-417.539062c0-122.664063-99.792969-222.460938-222.460937-222.460938zm67.121093 287.597656c-18.507812 18.503906-42.8125 27.757813-67.121093 27.757813-24.304688 0-48.617188-9.253907-67.117188-27.757813-37.011719-37.007812-37.011719-97.226562 0-134.238281 17.921875-17.929687 41.761719-27.804687 67.117188-27.804687 25.355468 0 49.191406 9.878906 67.121093 27.804687 37.011719 37.011719 37.011719 97.230469 0 134.238281zm0 0"></path></svg>
                    <span class="text-gray-600 mx-3">Location : {{ $event->location }}</span>
                </li>
            </ul>
            <div class="py-2">
                <p class="text-lg text-gray-800 font-semibold py-2">Description :</p>
                <p class="text-base text-gray-600 py-2 leading-loose">{{ $event->description }} </p>
            </div>
        </div>
    </div>
@endsection