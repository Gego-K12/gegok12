<form wire:submit.prevent="submitForm">
 
<div class="bg-white shadow px-4 py-3">

  @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
    <div class=""> <!-- tw-form-group -->
      <div class="flex flex-col lg:flex-row">
        <div class="w-full lg:w-1/3 md:w-8/12">
          <div class="w-full  lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="class_teacher_id" class="tw-form-label">Select Teacher<span class="text-red-500">*</span></label>
            </div>
            <div class="w-full lg:w-8/12 md:w-full">
            <div class="mb-2">
              <select class="tw-form-control w-full" id="class_teacher_id" wire:model="teacher_id" name="class_teacher_id" >
                <option value="">Select Class Teacher</option>
                @foreach($teachers as $teacher)
                <option value="{{$teacher->user_id}}" wire:click="load('{{$teacher->user_id}}')">{{$teacher->user->FullName}}</option>
                @endforeach
              </select>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="" ><!--  tw-form-group -->
      <table class="w-full lg:w-3/4 md:w-3/4 border my-3">
        <thead class="bg-gray-400">
          <tr class="border-b">
            <th class="tw-form-label py-2">StandardName</th>
            <th class="tw-form-label py-2">Subject<!-- <span class="text-red-500">*</span> --></th>
             <th class="tw-form-label py-2">No of Periods<!-- <span class="text-red-500">*</span> --></th>
           
            <th></th>
          </tr>
        </thead>
        <tbody>
               
          @if($details!=null ||$teacher_id!=null)

          @foreach($details as $key=>$value)
          <tr class="border-b">
            <td class="py-3 px-2">
               <!-- <input hidden class="tw-form-control w-full"  
              wire:model="standardlink_id.{{$key}}" value="{{$value->standardLink->id}}" /> -->
              <!--  <label class="tw-form-control w-full">{{$value->standardLink->id}}</label> -->
             <label class="tw-form-control w-full">{{$value->standardLink->StandardSection}}</label>
            </td>

            <td class="py-3 px-2">
              <!--  <input hidden class="tw-form-control w-full"  
              wire:model="subject_id.{{$key}}" value="{{$value->subject->id}}" /> -->
              <!-- <label class="tw-form-control w-full">{{$value->subject->id}}</label> -->
               <label class="tw-form-control w-full">{{$value->subject->name}}</label>
            </td>

             <td class="py-2 px-2">
              <input class="tw-form-control w-full"   wire:model="period_count.{{$value->id}}" value="{{$value->no_of_periods}}"/>
     
                 @error('period_count.'.$value->id) <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror  
            </td>
           
          </tr>
          @endforeach
          @else

          <tr class="border-b">
            <td class="py-2 px-2" colspan="3" align="center">
          <p class="text-sm">No class are alloted</p></td></tr>
          @endif
        </tbody>
      </table>
    </div>
    <div class="flex item-center align-right py-3">
            <h1 class="mx-2 my-1 font-semibold">Total Periods:{{ $total_count }}</h1>
    </div>
    
      <button class="btn btn-primary submit-btn">Submit</button>
    </div>
  </div>

 </div>

</form>