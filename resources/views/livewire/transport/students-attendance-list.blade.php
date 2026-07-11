<div>

    <div class="bg-white shadow px-4 py-3">

        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        
        <div class="search-filter text-right flex items-center justify-end">
               <div class="ml-3"><input type="text" class="datepicker tw-form-control" id="from_date" wire:model="from_date" placeholder="From Date"></div>
               <div class="ml-3"><input type="text" class="datepicker tw-form-control" id="to_date" wire:model="to_date" placeholder="To Date"> </div>
        </div>

        <div class="">
            <!--  tw-form-group -->
            <table class="w-full border my-3"> <!-- lg:w-3/4 md:w-3/4 -->
                <thead class="bg-gray-400">
                    <tr class="border-b">
                        <th class="tw-form-label px-2 py-2 text-left">Student</th>
                        <!-- <th class="tw-form-label py-2">Coordinator Incharge</th> -->
                        <th class="tw-form-label px-2 py-2 text-left">Route</th>
                        <th class="tw-form-label px-2 py-2 text-left">Type</th>
                        <th class="tw-form-label px-2 py-2 text-left">Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                 @if(count($students) > 0)
                @foreach ($students as $student)

                    <tr class="border-b">
                        <td class="py-3 px-2">
                            <label class="">{{ $student->student->firstname }} {{ $student->student->lastname }}</label>
                        </td>

                        <!-- <td class="py-3 px-2">
                            <label class="">{{ $student->student->name }}</label>
                        </td> -->

                        <td class="py-3 px-2">
                            <label class="">{{ $student->route->name }}</label>
                        </td>

                        @php
                            if($student->type == 'get_in')
                                $type = 'Boarding';
                            elseif($student->type == 'get_out')
                                $type = 'Drop';
                            else
                                $type = 'Others';
                        @endphp

                        <td class="py-3 px-2">
                            <label class="">{{ $type }}</label>
                        </td>

                        <td class="py-3 px-2">
                            <label class="">{{ $student->created_at->format('d-m-Y') }}</label>
                        </td>
                    </tr>
                    @endforeach
                     @else
                <tr><td class="py-3 px-2 text-center" colspan="6">No Records Found</td></tr>
                @endif
                        
                </tbody>
            </table>
        </div>
    </div>

</div>

{{ $students->links() }}

@push('scripts')
<script>
$(document).ready(function(){

    $("#from_date").datepicker({
        dateFormat: "yy-mm-dd",
        changeYear: true,
        changeMonth: true,
        onSelect: function (selected) {
             var dt = new Date(selected);

             @this.set('from_date', selected);

             dt.setDate(dt.getDate() + 1);
             $("#to_date").datepicker("option", "minDate", dt);
        }
    });

    $("#to_date").datepicker({
        dateFormat: "yy-mm-dd",
        changeYear: true,
        changeMonth: true,
        onSelect: function (selected) {
             var dt = new Date(selected);

             @this.set('to_date', selected);

             dt.setDate(dt.getDate() - 1);
             $("#from_date").datepicker("option", "maxDate", dt);
        }
    });
});
</script>
@endpush
