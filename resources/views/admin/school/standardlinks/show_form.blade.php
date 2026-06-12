<div>

  <div class="relative flex flex-row lg:flex-row md:flex-row bg-white shadow border">
    <div class="w-8/12 lg:w-8/12 md:w-7/12 flex flex-col lg:flex-row md:flex-row lg:items-center px-5 py-4">
      <div class="flex flex-col pr-8">
        <p class="font-semibold text-sm lg:text-base md:text-base text-gray-700 capitalize">Class Teacher </p>
        <p class="font-semibold text-lg lg::text-xl md:text-xl text-grey-darker capitalize flex items-center">
          <a href="{{ url('/admin/teacher/show/'.$standardLink->teacher->name) }}">{{ $standardLink->teacher->Fullname }}</a>
        </p>
      </div>
      <div class="flex flex-col lg:text-center lg:px-6">
        <p class="font-bold text-3xl lg:text-4xl md:text-4xl text-grey-darker capitalize">{{ $user_count }}</p>
        <p class="font-semibold text-base text-gray-700 capitalize">Students</p>
      </div>
    </div>
    <div class="flex lg:justify-end text-sm w-4/12 lg:w-4/12 md:w-5/12"> 
      <div class="flex flex-wrap lg:flex-row md:flex-row w-full justify-end">
        <!-- <a href="{{ url('/admin/attendance/add?standardLink_id='.$standardLink->id) }}" class="capitalize text-white custom-green rounded px-3 py-2 my-1 font-medium text-center mr-1 lg:mr-0 md:mr-0">record attendance</a> -->
        <div class="w-1/3 lg:w-1/4 md:w-1/3 text-center py-4">
          <a href="{{ url('/admin/attendance/add?standardLink_id='.$standardLink->id) }}" class="text-xs">
            <div class="bg-gray-200 rounded-full w-8 h-8 lg:w-10 md:h-10 md:w-10 md:h-10 flex items-center justify-center mx-auto hover:bg-gray-100">
              <svg class="w-4 h-4 lg:w-5 lg:h-5 md:w-5 md:h-5  fill-current text-gray-600" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 15.692 15.692" style="enable-background:new 0 0 15.692 15.692;" xml:space="preserve"><g><g><path  d="M2.996,5.11c0.037,0.223,0.123,0.364,0.208,0.453C3.406,6.909,4.531,8.158,5.56,8.158 c1.199,0,2.291-1.352,2.501-2.592c0.087-0.088,0.174-0.23,0.212-0.456c0.068-0.252,0.156-0.69,0.002-0.896 C8.267,4.204,8.258,4.193,8.25,4.185c0.145-0.529,0.328-1.623-0.327-2.368C7.865,1.743,7.497,1.304,6.712,1.072L6.337,0.943 C5.719,0.752,5.331,0.709,5.314,0.707c-0.028-0.002-0.057,0-0.084,0.007C5.209,0.72,5.135,0.74,5.078,0.732 c-0.148-0.021-0.37,0.055-0.409,0.07c-0.051,0.021-1.248,0.5-1.611,1.615c-0.034,0.09-0.179,0.564,0.014,1.726 c-0.029,0.02-0.055,0.044-0.077,0.073C2.839,4.42,2.927,4.858,2.996,5.11z"/><path  d="M7.784,13.594c-0.221-0.124-0.461-0.243-0.717-0.356c-0.124-0.055-0.25-0.107-0.375-0.156 c-0.098-0.037-0.214-0.085-0.295-0.106l-1.186-0.32L7.43,8.138l0.951,0.6C8.582,8.864,8.73,8.971,8.892,9.09l0.034,0.025 C9.087,9.234,9.245,9.356,9.4,9.482c0.337,0.272,0.635,0.538,0.912,0.813c0.021,0.021,0.041,0.04,0.062,0.061 c0.093-0.103,0.184-0.195,0.275-0.294c-0.116-0.345-0.257-0.664-0.429-0.92c0,0-0.244-0.333-0.823-0.555 c0,0-0.049-0.015-0.124-0.04C8.758,8.306,8.269,8.151,8.269,8.151C8.164,8.113,8.072,8.076,7.989,8.04 c-0.35-0.173-0.641-0.368-0.701-0.552c0,0,0.202,1.955-1.507,2.001L5.543,9.478C3.994,9.34,3.891,7.484,3.891,7.484 c-0.162,0.509-2.11,1.101-2.11,1.101C1.202,8.807,0.957,9.141,0.957,9.141C0.101,10.411,0,13.237,0,13.237 c0.011,0.646,0.29,0.713,0.29,0.713c1.969,0.879,5.058,1.034,5.058,1.034c0.167,0.004,0.322-0.005,0.477-0.014l0.004,0.016 c0,0,1.508-0.077,3.089-0.423L8.725,14.31C8.568,14.103,8.217,13.836,7.784,13.594z"/><path d="M7.222,7.571c0.021-0.027,0.044-0.054,0.066-0.084C7.283,7.469,7.282,7.46,7.282,7.46 C7.263,7.499,7.241,7.532,7.222,7.571z"/><path  d="M3.9,7.481L3.895,7.46L3.891,7.482C3.892,7.478,3.896,7.474,3.897,7.47 C3.898,7.471,3.899,7.475,3.9,7.481z"/><path d="M13.882,8.388c-0.561,0.396-1.084,0.844-1.582,1.315c-0.499,0.474-0.972,0.973-1.427,1.488 c-0.169,0.192-0.333,0.386-0.496,0.581c-0.002-0.003-0.004-0.006-0.005-0.009c-0.24-0.32-0.5-0.605-0.77-0.872 c-0.27-0.266-0.55-0.512-0.838-0.746c-0.145-0.116-0.291-0.23-0.44-0.342C8.169,9.691,8.033,9.59,7.843,9.47l-1.182,2.405 c0.108,0.029,0.265,0.09,0.398,0.142c0.141,0.054,0.279,0.112,0.417,0.173c0.276,0.122,0.545,0.255,0.802,0.398 c0.508,0.284,0.981,0.63,1.251,0.983l0.909,1.192l0.523-1.134c0.263-0.568,0.578-1.162,0.901-1.728 c0.326-0.57,0.674-1.129,1.051-1.668s0.781-1.06,1.233-1.54c0.452-0.477,0.951-0.921,1.546-1.236 C15.046,7.649,14.442,7.996,13.882,8.388z"/></g></g></svg>
            </div>
            <span class="hover:font-semibold hidden lg:block md:block">Attendance</span>
          </a>
        </div>

        {{-- @if(count($standardLink->timetable) == 0)
           <a href="{{ url('/admin/timetable/add?standardLink_id='.$standardLink->id) }}" class="capitalize text-white custom-green rounded px-3 py-2 my-1 font-medium text-center mr-1 lg:mr-0 md:mr-0">add timetable</a> 
         @endif --}}

       <!--  <a href="{{ url('/admin/attendance/export/'.$standardLink->id) }}" class="capitalize text-white blue-bg rounded px-3 py-2 my-1 font-medium text-center mr-1 lg:mr-0 md:mr-0">export attendance</a> -->
      

      <!--   <a href="{{ url('/admin/homework/add?standardLink_id='.$standardLink->id) }}" class="capitalize text-white blue-bg rounded px-3 py-2 my-1 font-medium text-center mr-1 lg:mr-0 md:mr-0">give homework</a> -->
      <div class="w-1/3 lg:w-1/4 md:w-1/3 text-center py-4">
        <a href="{{ url('/admin/homework/add?standardLink_id='.$standardLink->id) }}" class="text-xs">
          <div class="bg-gray-200 rounded-full w-8 h-8 lg:w-10 md:h-10 md:w-10 md:h-10 flex items-center justify-center mx-auto hover:bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" id="Capa_1" enable-background="new 0 0 512 512" height="512px" viewBox="0 0 512 512" width="512px" class="w-4 h-4 lg:w-5 lg:h-5 md:w-5 md:h-5 fill-current text-gray-600"><g><g><path d="m74.791 114.523c26.424.368 67.758 5.62 114.616 28.55 7.392 3.619 16.401.608 20.066-6.88 3.641-7.441.561-16.425-6.881-20.067-51.852-25.374-97.892-31.189-127.384-31.6-.071-.001-.143-.001-.213-.001-8.187 0-14.88 6.579-14.994 14.791-.115 8.283 6.507 15.091 14.79 15.207z" data-original="#000000" data-old_color="#000000" fill="" class="active-path"></path><path d="m202.593 176.126c-51.852-25.374-97.892-31.189-127.384-31.6-.071-.001-.143-.001-.213-.001-8.187 0-14.88 6.579-14.994 14.791-.116 8.284 6.506 15.092 14.789 15.208 26.424.368 67.758 5.62 114.616 28.55 7.392 3.619 16.401.608 20.066-6.88 3.641-7.443.561-16.427-6.88-20.068z" data-original="#000000" data-old_color="#000000" fill="" class="active-path"></path><path d="m202.593 236.126c-51.852-25.374-97.892-31.189-127.384-31.6-.071-.001-.143-.001-.213-.001-8.187 0-14.88 6.579-14.994 14.791-.116 8.284 6.506 15.092 14.789 15.208 26.424.368 67.758 5.62 114.616 28.55 7.392 3.619 16.401.608 20.066-6.88 3.641-7.443.561-16.427-6.88-20.068z" data-original="#000000" data-old_color="#000000" fill="" class="active-path"></path><path d="m309.407 116.126c-7.441 3.641-10.521 12.625-6.881 20.067 2.604 5.32 7.937 8.41 13.484 8.41 2.213 0 4.461-.492 6.582-1.53 37.64-18.419 75.865-28.024 113.616-28.55 8.283-.115 14.905-6.924 14.789-15.208-.115-8.283-6.876-14.901-15.207-14.79-42.213.589-84.735 11.221-126.383 31.601z" data-original="#000000" data-old_color="#000000" fill="" class="active-path"></path><path d="m500.638 7.584c-18.59-4.713-37.148-7.584-60.591-7.584-.016 0-.035 0-.05 0-43.627.009-110.281 11.25-183.997 63.569-73.272-52-139.55-63.382-182.937-63.566-24.136-.104-43.055 2.852-61.7 7.581-6.678 1.67-11.363 7.669-11.363 14.552v294.5c0 4.619 2.128 8.98 5.769 11.823 6.635 5.182 13.606 2.463 13.981 2.448 41.952-10.63 127.258-17.266 227.25 57.729 2.667 2 5.833 3 9 3s6.333-1 9-3c31.188-23.391 63.401-40.556 96-51.235v159.599c0 8.284 6.716 15 15 15h60c8.284 0 15-6.716 15-15v-172.236c21.132 1.097 36.088 4.848 41.675 6.252.277.008 7.061 2.515 13.557-2.557 3.641-2.843 5.769-7.204 5.769-11.823v-294.5c-.001-6.883-4.685-12.882-11.363-14.552zm-470.638 290.414v-263.956c9.661-1.955 24.286-4.124 42.937-4.04 39.466.168 100.214 10.809 168.063 59.726v258.166c-45.801-29.614-93.332-47.095-141.641-52.035-29.797-3.047-53.622-.62-69.359 2.139zm385.485-56.998h-18.969l9.484-29.8zm5.515 241h-30v-211h30zm61-183.931c-8.266-1.429-18.763-2.783-31-3.342v-38.667c.009-1.64-.255-3.321-.813-4.942l-29.894-93.926c-2.22-6.938-9.127-11.275-16.273-10.32-31.752 4.227-63.585 14.069-94.613 29.253-7.441 3.641-10.521 12.625-6.881 20.066 2.604 5.32 7.937 8.41 13.484 8.41 2.213 0 4.461-.492 6.582-1.53 20.297-9.932 40.912-17.319 61.544-22.097l-22.324 70.143c-.521 1.513-.806 3.135-.813 4.823v50.053c-30.537 8.934-60.681 22.965-90 41.91v-258.175c68.249-49.206 129.329-59.72 169.004-59.728h.043c18.182 0 32.464 2.124 41.954 4.043z" data-original="#000000" data-old_color="#000000" fill="" class="active-path"></path></g></g></svg>
          </div>
        <span class="hover:font-semibold hidden lg:block md:block">Home work<span>
      </a>
        </div>

        <div class="w-1/3 lg:w-1/4 md:w-1/3 text-center py-4">
        <a href="{{ url('/admin/standardLink/id-card/'.$standardLink->id) }}" class="text-xs">
          <div class="bg-gray-200 rounded-full w-8 h-8 lg:w-10 md:h-10 md:w-10 md:h-10 flex items-center justify-center mx-auto hover:bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 lg:w-5 lg:h-5 md:w-5 md:h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
</svg>
          </div>
        <span class="hover:font-semibold hidden lg:block md:block">Id card<span>
      </a>
        </div>

        @if(config('gtransport.enabled', false))
        <div class="w-1/3 lg:w-1/4 md:w-1/3 text-center py-4">
        <a href="{{ url('/admin/student/buspass/show/'.$standardLink->id) }}" class="text-xs">
          <div class="bg-gray-200 rounded-full w-8 h-8 lg:w-10 md:h-10 md:w-10 md:h-10 flex items-center justify-center mx-auto hover:bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 lg:w-5 lg:h-5 md:w-5 md:h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
</svg>
          </div>
        <span class="hover:font-semibold hidden lg:block md:block">Bus Pass<span>
      </a>
        </div>
        @endif

        <div class="w-1/3 lg:w-1/4 md:w-1/3 text-center py-4">
          <a href="javascript:void(0)" onclick="openModal()">
            <div class="bg-gray-200 rounded-full w-8 h-8 lg:w-10 md:h-10 md:w-10 flex items-center justify-center mx-auto hover:bg-gray-100">
              
              <!-- Simple Group Icon -->
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 lg:w-5 lg:h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M7 7a4 4 0 118 0 4 4 0 01-8 0zm10 4a4 4 0 110-8 4 4 0 010 8z"/>
              </svg>

            </div>
            <span class="hover:font-semibold hidden lg:block md:block">Groups</span>
          </a>
        </div>

        <div class=" mb-2 px-2 py-2">
          <div class="bg-gray-200 rounded-full w-6 h-6 ml-auto flex items-center justify-center cursor-pointer" onclick="show('showdetail')">
            <svg class="w-3 h-3 fill-current text-gray-600" id="Capa_1" enable-background="new 0 0 515.555 515.555" height="512" viewBox="0 0 515.555 515.555" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m303.347 18.875c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0"/><path d="m303.347 212.209c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0"/><path d="m303.347 405.541c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0"/></svg>
          </div>
          
           <div id="showdetail" class="hidden attendance-class-detail shadow rounded mx-2 absolute right-0 bg-white">
            <ul class="text-sm leading-loose">
              @if(config('gtimetable.enabled', false))
              <li class="py-1">
                @if(count($standardLink->timetable) == 0)
                  <a href="{{ url('/admin/timetable/add?standardLink_id='.$standardLink->id) }}" class="capitalize px-3 py-2 my-1 font-medium text-center">add timetable</a>
                @endif
              </li>
              @endif
              <li class="py-1">
                <a href="{{ url('/admin/attendance/export/'.$standardLink->id) }}" class="capitalize px-3 py-2 my-1 font-medium text-center mr-1 lg:mr-0 md:mr-0">export attendance</a>
              </li>
            </ul>
          </div>
          
        </div>
      </div>   
    </div>
  </div>
  <div class="bg-white shadow border my-3">
    <class-tab url="{{url('/')}}" school_id="{{ $standardLink->school_id }}" id="{{ $standardLink->id }}" mode="admin" auth_id="{{ \Auth::id() }}"></class-tab>
    <div id="class"></div>
    <div id="notes"></div>
  </div>

  <div id="groupModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center">
    
    <div class="bg-white p-6 rounded-lg w-full max-w-lg shadow-lg">
        
        <h2 class="font-bold text-lg mb-4">Add Group</h2>

        <!-- Input -->
        <input 
            type="text" 
            id="group_name" 
            class="border w-full p-2 rounded mb-1" 
            placeholder="Enter Group Name"
        >

        <!-- Validation error -->
        <p id="group_error" class="text-red-500 text-sm mb-3 hidden"></p>

        <!-- Buttons -->
        <div class="flex justify-end mt-4">
            <button onclick="closeModal()" class="mr-2 px-4 py-2 bg-gray-300 rounded">
                Cancel
            </button>

            <button onclick="saveGroup()" class="px-4 py-2 bg-green-500 text-white rounded">
                Save
            </button>
        </div>

    </div>
</div>
</div>

@push('scripts')
  <script>
    function show(id)
    {
      if($('#'+id).hasClass('hidden'))
      {
        $('#'+id).removeClass('hidden').addClass('block');
          //$('.active_call_icon').addClass('active');
      }
      else
      {
        $('#'+id).removeClass('block').addClass('hidden');
        //$('.active_call_icon').removeClass('active');
      }
    }
  </script>
  <script>
function openModal() {
    let modal = document.getElementById('groupModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    let modal = document.getElementById('groupModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');

    // reset input + error
    document.getElementById('group_name').value = '';
    document.getElementById('group_error').classList.add('hidden');
}

function saveGroup() {
    let name = document.getElementById('group_name').value;
    let errorBox = document.getElementById('group_error');

    // reset error
    errorBox.classList.add('hidden');
    errorBox.innerText = '';

    // frontend validation
    if (!name) {
        errorBox.innerText = "Group name is required";
        errorBox.classList.remove('hidden');
        return;
    }

    axios.post('/admin/group/store', {
        group_name: name,
        standards_link_id: {{ $standardLink->id }}
    })
    .then(response => {
        closeModal();
        location.reload();
    })
    .catch(error => {
        if (error.response && error.response.data.errors) {
            errorBox.innerText = Object.values(error.response.data.errors)[0][0];
            errorBox.classList.remove('hidden');
        }
    });
}
</script>
@endpush