<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('layouts.partials.favicon')
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'GegoK12') }}</title>
        <!-- Styles -->

        <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;500&family=IBM+Plex+Sans:wght@500;600;700&family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

         <script>
        window.User = {!! json_encode(optional(auth()->user())->only('id')) !!}
    </script>

    <!-- <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script> -->

    <!-- new -->
    <script>
       window.AppConfig = {
          gquiz_enabled: @json(config('gquiz.enabled')),
          gexam_enabled: @json(config('gexam.enabled')),
          ginventory_enabled: @json(config('ginventory.enabled')),
          gchat_enabled: @json(config('gchat.enabled')),
          gtransport_enabled: @json(config('gtransport.enabled')),
          gcertificate_enabled: @json(config('gcertificate.enabled')),
          gtimetable_enabled: @json(config('gtimetable.enabled')),
          gvideoroom_enabled: @json(config('gvideoroom.enabled')),
          galumni_enabled: @json(config('galumni.enabled')),
          gfee_enabled: @json(config('gfee.enabled'))

       };
    </script>
    <!-- end -->

 <livewire:styles>

    </head>
    <body class="font-primary antialiased min-h-screen overflow-x-hidden">
        <div id="app">
            @yield('base-navigation')
            <main class="flex w-full h-full min-h-screen">
                <div class="sidebar min-h-full">
                    @yield('base-sidebar')
                </div>
                <div class="bg-gray-200 grow w-full px-4 py-8" style="width: calc(100vw - 195px);">
                    @yield('base-content')
                </div>
            </main>
            @yield('base-footer')
        </div>


        <!-- Scripts -->

        <script src="{{ mix('js/manifest.js') }}"></script>
        <script src="{{ mix('js/vendor.js') }}"></script>
        <script src="{{ mix('js/app.js') }}"></script>
        <script src="{{ asset('js/custom.js') }}" ></script>
        @stack('scripts')

        <livewire:scripts>
            <!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>

        <script>
   window.addEventListener('alert', event => {
        toastr[event.detail.type](event.detail.message,
            event.detail.title ?? ''), toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": 'bottom-right',

            }
    });

   window.addEventListener('registeralert', event => {
        toastr[event.detail.type](event.detail.message,
            event.detail.title ?? ''), toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": 'bottom-right',

            }
    });
</script>

    </body>
    <style>
    .hide-menu {
    display: none;
}
</style>
    <style>
    .page-loading .loading {
  margin: auto;
  height: 100px;
  width: 100px;
  animation: spinner 1.5s linear infinite !important;
}
.page-loading .loading > div {
  height: inherit;
  width: inherit;
  position: absolute;
  animation-name: opacity;
  animation-duration: 1.1s;
  animation-timing-function: ease;
  animation-iteration-count: infinite;
  opacity: 0;
}
.page-loading .loading > div > div {
  height: 11px;
  width: 11px;
  border-radius: 50%;
  background: #9b2c2c;
  position: absolute;
  top: 0%;
  right: 50%;
  transform: translate(50%, 0%);
}
.page-loading .loading > div:nth-child(2) {
  transform: rotate(30deg);
  animation-delay: 0.1s;
}
.page-loading .loading > div:nth-child(3) {
  transform: rotate(60deg);
  animation-delay: 0.2s;
}
.page-loading .loading > div:nth-child(4) {
  transform: rotate(90deg);
  animation-delay: 0.3s;
}
.page-loading .loading > div:nth-child(5) {
  transform: rotate(120deg);
  animation-delay: 0.4s;
}
.page-loading .loading > div:nth-child(6) {
  transform: rotate(150deg);
  animation-delay: 0.5s;
}
.page-loading .loading > div:nth-child(7) {
  transform: rotate(180deg);
  animation-delay: 0.6s;
}
.page-loading .loading > div:nth-child(8) {
  transform: rotate(210deg);
  animation-delay: 0.7s;
}
.page-loading .loading > div:nth-child(9) {
  transform: rotate(240deg);
  animation-delay: 0.8s;
}
.page-loading .loading > div:nth-child(10) {
  transform: rotate(270deg);
  animation-delay: 0.9s;
}
.page-loading .loading > div:nth-child(11) {
  transform: rotate(300deg);
  animation-delay: 1s;
}
.page-loading .loading > div:nth-child(12) {
  transform: rotate(330deg);
  animation-delay: 1.1s;
}
@keyframes opacity {
  0% {
    opacity: 0.2;
  }
  50% {
    opacity: 1;
  }
  100% {
    opacity: 0.2;
  }
}
    </style>
</html>
