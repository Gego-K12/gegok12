<div>

  <div class="relative flex flex-col lg:flex-row bg-white shadow border">

    <div class="class-head-info px-5 py-4">
      <div class="flex flex-col">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Class Teacher</p>
        <p class="font-semibold text-lg text-gray-800 capitalize mt-1">
          <a href="{{ url('/admin/teacher/show/'.$standardLink->teacher->name) }}" class="class-head-info-link">{{ $standardLink->teacher->Fullname }}</a>
        </p>
      </div>
      <div class="flex flex-col class-head-info-item--divider">
        <p class="font-bold text-3xl text-gray-800">{{ $user_count }}</p>
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Students</p>
      </div>
    </div>

    <div class="class-head-actions px-3 py-3">

      <a href="{{ url('/admin/attendance/add?standardLink_id='.$standardLink->id) }}" class="class-head-action">
        <span class="class-head-action-icon"><i class="fa-solid fa-calendar-check"></i></span>
        <span class="class-head-action-label">Attendance</span>
      </a>

      <a href="{{ url('/admin/homework/add?standardLink_id='.$standardLink->id) }}" class="class-head-action">
        <span class="class-head-action-icon"><i class="fa-solid fa-book-open"></i></span>
        <span class="class-head-action-label">Home Work</span>
      </a>

      <a href="{{ url('/admin/standardLink/id-card/'.$standardLink->id) }}" class="class-head-action">
        <span class="class-head-action-icon"><i class="fa-solid fa-id-card"></i></span>
        <span class="class-head-action-label">Id Card</span>
      </a>

      @if(config('gtransport.enabled', false))
      <a href="{{ url('/admin/student/buspass/show/'.$standardLink->id) }}" class="class-head-action">
        <span class="class-head-action-icon"><i class="fa-solid fa-bus"></i></span>
        <span class="class-head-action-label">Bus Pass</span>
      </a>
      @endif

      <a href="javascript:void(0)" onclick="openModal()" class="class-head-action">
        <span class="class-head-action-icon"><i class="fa-solid fa-users"></i></span>
        <span class="class-head-action-label">Groups</span>
      </a>

      <div class="relative">
        <button type="button" class="class-head-action" onclick="show('showdetail')">
          <span class="class-head-action-icon"><i class="fa-solid fa-ellipsis-vertical"></i></span>
          <span class="class-head-action-label">More</span>
        </button>

        <div id="showdetail" class="hidden class-head-dropdown">
          <ul class="text-sm">
            @if(config('gtimetable.enabled', false))
              @if(count($standardLink->timetable) == 0)
              <li>
                <a href="{{ url('/admin/timetable/add?standardLink_id='.$standardLink->id) }}">Add Timetable</a>
              </li>
              @endif
            @endif
            <li>
              <a href="{{ url('/admin/attendance/export/'.$standardLink->id) }}">Export Attendance</a>
            </li>
          </ul>
        </div>
      </div>

    </div>
  </div>
  <div class="bg-white shadow border my-3">
    <class-tab url="{{url('/')}}" school_id="{{ $standardLink->school_id }}" id="{{ $standardLink->id }}" mode="admin" auth_id="{{ \Auth::id() }}"></class-tab>
    <div id="class"></div>
    <div id="notes"></div>
  </div>

  @livewire('admin.profile-extra-tabs', ['entityId' => $standardLink->id, 'scope' => 'class'])

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