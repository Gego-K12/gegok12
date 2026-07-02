@extends('layouts.empty')

@section('content')
<div class="w-full flex flex-col mx-2  lg:mx-0 md:mx-0">
@include('layouts.partials.logo')
<div class="w-full lg:w-1/3 p-8 mx-auto bg-white relative">
  @include('partials.message')
  <div class="justify-content-center">
    
    <div class="rounded-full"></div>
    <div class="card">
      <div class="card-header   text-center text-2xl font-semibold tracking-wide">{{ __('Login') }}</div>
      <div class="card-body">
        @if(\Config::get('settings.login_status', 1)==0)
        <div class="alert-box success">
          Login page under maintenance
        </div>
        @else
        <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
          @csrf
          <div class="lg:px-5 md:px-5">
            <div class="form-group my-8">
              <!-- <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label> -->
              <div class="relative">
                <div class="input-group flex  w-full border-b border-gray-400">
                  <span class="input-group-addon w-6 flex items-center justify-center" style="color: #aaa;">
         <svg class="fill-current w-4 h-4" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
     viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<g>
    <g>
        <path d="M469.333,64H42.667C19.135,64,0,83.135,0,106.667v298.667C0,428.865,19.135,448,42.667,448h426.667
            C492.865,448,512,428.865,512,405.333V106.667C512,83.135,492.865,64,469.333,64z M42.667,85.333h426.667
            c1.572,0,2.957,0.573,4.432,0.897c-36.939,33.807-159.423,145.859-202.286,184.478c-3.354,3.021-8.76,6.625-15.479,6.625
            s-12.125-3.604-15.49-6.635C197.652,232.085,75.161,120.027,38.228,86.232C39.706,85.908,41.094,85.333,42.667,85.333z
             M21.333,405.333V106.667c0-2.09,0.63-3.986,1.194-5.896c28.272,25.876,113.736,104.06,169.152,154.453
            C136.443,302.671,50.957,383.719,22.46,410.893C21.957,409.079,21.333,407.305,21.333,405.333z M469.333,426.667H42.667
            c-1.704,0-3.219-0.594-4.81-0.974c29.447-28.072,115.477-109.586,169.742-156.009c7.074,6.417,13.536,12.268,18.63,16.858
            c8.792,7.938,19.083,12.125,29.771,12.125s20.979-4.188,29.76-12.115c5.096-4.592,11.563-10.448,18.641-16.868
            c54.268,46.418,140.286,127.926,169.742,156.009C472.552,426.073,471.039,426.667,469.333,426.667z M490.667,405.333
            c0,1.971-0.624,3.746-1.126,5.56c-28.508-27.188-113.984-108.227-169.219-155.668c55.418-50.393,140.869-128.57,169.151-154.456
            c0.564,1.91,1.194,3.807,1.194,5.897V405.333z"/>
    </g>
</g> </svg>
                  </span>
                  <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} px-2 py-2 w-full text-sm focus:outline-none inputAnimation" placeholder="" name="email" value="{{ old('email') }}" required>
                  <label for="email" class="control-label text-sm">E-Mail Address/ Registration Number</label>
                </div>
                
              </div>
              @if ($errors->has('email'))
              <span class="invalid-feedback text-red-500 text-xs font-semibold" role="alert">
                {{ $errors->first('email') }}
              </span>
              @endif
            </div>
            <div class="form-group mt-8 mb-3">
              <!-- <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label> -->
              <div class="relative">
                <div class="input-group flex  w-full border-b border-gray-400">
                  <span class="input-group-addon w-6 flex items-center justify-center" style="color: #aaa;">
                
<svg class="fill-current w-4 h-4" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
     viewBox="0 0 477.867 477.867" style="enable-background:new 0 0 477.867 477.867;" xml:space="preserve">
<g>
    <g>
        <path d="M409.6,170.667h-17.067V153.6C392.439,68.808,323.725,0.094,238.933,0c-84.792,0.094-153.506,68.808-153.6,153.6v17.067
            H68.267c-9.426,0-17.067,7.641-17.067,17.067V460.8c0,9.426,7.641,17.067,17.067,17.067H409.6c9.426,0,17.067-7.641,17.067-17.067
            V187.733C426.667,178.308,419.026,170.667,409.6,170.667z M119.467,153.6c0-65.98,53.487-119.467,119.467-119.467
            S358.4,87.62,358.4,153.6v17.067H119.467V153.6z M392.533,443.733h-307.2V204.8h307.2V443.733z"/>
    </g>
</g>
<g>
    <g>
        <path d="M287.209,290.111c-7.211-20.472-26.571-34.152-48.276-34.111c-28.211-0.053-51.124,22.773-51.177,50.984
            c-0.041,21.705,13.639,41.065,34.111,48.276v37.274c0,9.426,7.641,17.067,17.067,17.067S256,401.959,256,392.533V355.26
            C282.609,345.888,296.582,316.719,287.209,290.111z M238.933,324.267c-9.426,0-17.067-7.641-17.067-17.067
            s7.641-17.067,17.067-17.067S256,297.774,256,307.2S248.359,324.267,238.933,324.267z"/>
    </g>
</g></svg>

                  </span>
                  <input id="password" placeholder="" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} px-2 py-2 w-full  text-sm focus:outline-none inputAnimation" name="password" required>
                  <label for="password" class="control-label text-sm">Password</label>
                </div>
              </div>
              @if ($errors->has('password'))
              <span class="invalid-feedback text-red-500 text-xs" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
              </span>
              @endif
            </div>
            <div class="form-group pt-4">
              <div class="flex justify-between">
                <div class="form-check flex items-center">
                  <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                  <label class="form-check-label  text-sm mx-1" for="remember">
                    {{ __('Remember Me') }}
                  </label>
                </div>
                <div>
                  <a class="form-check-label text-xs" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                  </a>
                </div>
              </div>
            </div>
            <div class="form-group  py-8 flex justify-between">
              <div class="w-full z-40">
                <button type="submit" class="btn bg-red-600 text-white uppercase px-8 py-1 tracking-wider w-full z-40" id="login">
                {{ __('Login') }}
                </button>
              </div>
              
            </div>
            <div>
              <!-- <p class="form-check-label text-center tracking-wider">To Create an Account click <a href="{{ url('/register') }}" class="underline text-blue-500">Signup</a></p> -->
            </div>
          </div>
        </form>
        @endif
      </div>
    </div>
  </div>
</div>
</div>
@endsection

