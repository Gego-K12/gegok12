<div class="w-full lg:w-48 md:w-48 h-full bg-gray-900 text-white siteadmin-sidebar">
  <div class="min-h-full header-wrapper-b hidden lg:block md:block">
   @include('layouts.siteadmin._menu')
  </div>
</div>
<div id="res_sidebar" class="w-full lg:w-48 md:w-48 hidden lg:hidden md:hidden res_sidebar siteadmin-sidebar">
  <div class="min-h-full header-wrapper-b lg:hidden md:hidden bg-gray-800 text-white">
   @include('layouts.siteadmin._menu')
  </div>
</div>
