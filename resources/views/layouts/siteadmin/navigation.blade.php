<nav class="navbar bg-white shadow-lg w-full flex lg:flex-row px-4 lg:px-8 py-2 justify-between items-center">
  <div class="nav-brand flex items-center">
    <button class="block lg:hidden md:hidden mr-3" onclick="showsidebar('res_sidebar')">
      <span class="navbar-toggler-icon">
        <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" fill="currentColor" d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"/></svg>
      </span>
    </button>
    <span class="text-lg lg:text-xl font-exo font-medium text-gray-800">
      <strong>GegoK12 Platform Admin</strong>
    </span>
  </div>

  <div class="flex items-center">
    <li class="list-none">
      <div class="profile-click" dusk="profile-menu">
        <img src="{{ asset('uploads/user/avatar/default-user.jpg') }}" class="w-8 h-8 rounded-full cursor-pointer">
        <div class="user-dtl rounded">
          <ul class="list-reset">
            <div class="flex border-b p-2 items-center">
              <img src="{{ asset('uploads/user/avatar/default-user.jpg') }}" class="w-10 h-10 rounded-full cursor-pointer">
              <div>
                <div>
                  <a class="nav-link text-sm no-underline text-black px-2" href="{{ url('/plugins') }}">
                    {{ Auth::user()->FullName }} <span class="caret"></span>
                  </a>
                </div>
                <div>
                  <p class="text-sm no-underline text-black px-2">{{ Auth::user()->email }}</p>
                </div>
              </div>
            </div>
            <div class="py-2 leading-loose">
              <li class="hover:bg-gray-200">
                <a class="dropdown-item text-sm no-underline text-black px-3" dusk="logout-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
              </li>
            </div>
          </ul>
        </div>
      </div>
    </li>
  </div>
</nav>
