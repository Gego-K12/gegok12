{{--
    Shared sidebar wrapper for every portal. Included with a $portal config
    array and the portal key from layouts/_common/layout.blade.php.

    Renders two wrapping divs (desktop + mobile), each including that
    portal's own _menu.blade.php (the data-driven Font Awesome sidebar item
    list from an earlier, unrelated session) -- unchanged by this consolidation.
--}}
<div class="w-full lg:w-48 md:w-48 {{ $portal['sidebar']['desktopClass'] }}">
    <div class="min-h-full header-wrapper-b hidden lg:block md:block">
        @include('layouts.'.$portalKey.'._menu')
    </div>
</div>
<div id="{{ $portal['sidebar']['mobileId'] }}" class="w-full lg:w-48 md:w-48 hidden lg:hidden md:hidden res_sidebar {{ $portal['sidebar']['mobileOuterClass'] }}">
    <div class="min-h-full header-wrapper-b lg:hidden md:hidden {{ $portal['sidebar']['mobileInnerClass'] }}">
        @include('layouts.'.$portalKey.'._menu')
    </div>
</div>
