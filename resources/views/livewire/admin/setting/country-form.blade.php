  
  <div class="p-6">

    <div class="bg-white shadow-lg rounded-xl border overflow-hidden">
        <div class="flex items-center px-6 py-4 border-b bg-gray-50">
            <div class="bg-gray-200 p-1 rounded-full">
                <a href="{{ url('admin/setting/countries') }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg></a>
            </div>

            <div class="text-xl ml-3 font-bold">
                 Update Country
            </div>
        </div>

    <div class="p-6">

 <form wire:submit.prevent="submitCountry" method="POST" class="">
        @csrf 

    <div class="flex flex-col lg:flex-col">

     <div class="flex flex-col lg:flex-row">
        <div class="w-full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8 mb-2">
        <div class="mb-2">
        <label for="" class="tw-form-label">Name<span class="text-red-500">*</span></label>
        </div>
          <input name="name" value="" type="text" placeholder="Name" class="tw-form-control w-full" wire:model.live="name">
          @error('name')<span class="text-red-600 text-xs font-normal"><strong>{{ $message }}</strong></span>@enderror
        </div>
        </div>
      </div>

      <div class="flex flex-col lg:flex-row">
        <div class="w-full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8 mb-2">
        <div class="mb-2">
        <label for="" class="tw-form-label">Short Name<span class="text-red-500">*</span></label>
        </div>
          <input name="short_name" value="" type="text" placeholder="Short Name" class="tw-form-control w-full" wire:model.live="short_name">
          @error('short_name')<span class="text-red-600 text-xs font-normal"><strong>{{ $message }}</strong></span>@enderror
        </div>
        </div>
      </div>

      <div class="flex flex-col lg:flex-row">
        <div class="w-full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8 mb-2">
        <div class="mb-2">
        <label for="" class="tw-form-label">ISO Code<span class="text-red-500">*</span></label>
        </div>
          <input name="iso_code" value="" type="text" placeholder="ISO Code" class="tw-form-control w-full" wire:model.live="iso_code">
          @error('iso_code')<span class="text-red-600 text-xs font-normal"><strong>{{ $message }}</strong></span>@enderror
        </div>
        </div>
      </div>

      <div class="flex flex-col lg:flex-row">
        <div class="w-full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8 mb-2">
        <div class="mb-2">
        <label for="" class="tw-form-label">Tel Prefix<span class="text-red-500">*</span></label>
        </div>
          <input name="tel_prefix" value="" type="text" placeholder="Tel Prefix" class="tw-form-control w-full" wire:model.live="tel_prefix">
          @error('tel_prefix')<span class="text-red-600 text-xs font-normal"><strong>{{ $message }}</strong></span>@enderror
        </div>
        </div>
      </div>


      <div class="tw-form-group w-full lg:w-1/2">
       <div class="lg:mr-8 md:mr-8 ">
        <div>
          <label class="text-sm font-semibold">Status<span class="text-red-500">*</span></label>
          <div class="p-2 border border-gray-300 rounded text-sm mt-1">
           <div class="flex gap-3">
            <div class="flex items-center">
            <div><input  name="status" type="radio" class="block" wire:model="status" value='1' /></div>
            <span class="mx-2">Active </span> 
        </div>
        <div class="flex items-center">
         <div><input name="status" type="radio" class="mt-1 block" wire:model="status" value='0'/></div>
         <span class="mx-2">Inactive</span>
       </div>
    	</div>
       </div>
       @error('status') 
        <span class="text-red-600 text-xs font-normal"><strong>{{ $message }}</strong></span> 
        @enderror
        </div>
      </div>
      </div>

      {{-- <div class="my-6">
        <input type="submit" class="btn btn-primary submit-btn cursor-pointer" value="Submit"/> 
        <a href="#" class="btn btn-reset reset-btn">Reset</a>
      </div> --}}

      <div class="my-5 pb-5 flex items-center">
      <div wire:loading.attr="disabled" class="submit-btn w-max mr-2 btn btn-primary">
      <svg wire:loading wire:target="submitCountry"
      class="w-4 h-4 mx-1 text-blue animate-spin"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24">
      <circle class="opacity-25"
          cx="12"
          cy="12"
          r="10"
          stroke="currentColor"
          stroke-width="4">
      </circle>
      <path
          class="opacity-75"
          fill="currentColor"
          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
      </path>
      </svg>
      <input type="submit" class="text-white bg-transparent cursor-pointer" value="Submit"/>
    </div>
      <a href="#" class="btn btn-reset reset-btn">Reset</a>
    </div>

  </div>
</form>
</div>
</div>
</div>