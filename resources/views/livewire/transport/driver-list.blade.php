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
                        <th class="tw-form-label px-2 py-2 text-left">Driver</th>
                        <th class="tw-form-label px-2 py-2 text-left">Route</th>
                        <th class="tw-form-label px-2 py-2 text-left">Type</th>
                        <th class="tw-form-label px-2 py-2 text-left">Start Km</th>
                        <th class="tw-form-label px-2 py-2 text-left">End Km</th>
                        <th class="tw-form-label px-2 py-2 text-left">Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                @if(count($driverTrips) > 0)
                @foreach ($driverTrips as $driverTrip)

                    @php
                        if($driverTrip->type == 'in'){
                            $type = 'School Trip In';
                        }elseif($driverTrip->type == 'out'){
                            $type = 'School Trip Out';
                        }else{
                            $type = 'Others';
                        }

                        if($driverTrip->end_km != null){
                            $endKm = $driverTrip->end_km;
                        }else{
                            $endKm = '--';
                        }
                    @endphp

                    <tr class="border-b">
                        <td class="py-3 px-2">
                            <label
                                class="">{{ $driverTrip->driver->userprofile->firstname }} {{ $driverTrip->driver->userprofile->lastname }}</label>
                        </td>

                        <td class="py-3 px-2">
                            <label class="">{{ $driverTrip->route->name }}</label>
                        </td>

                        <td class="py-3 px-2">
                            <label class="">{{ $type }}</label>
                        </td>

                        <td class="py-3 px-2">
                            <label class="">{{ $driverTrip->start_km }}</label>
                        </td>

                        <td class="py-3 px-2">
                            <label class="">{{ $endKm }}</label>
                        </td>

                        <td class="py-2 px-2">
                            <label class="">{{ $driverTrip->created_at->format('d-m-Y') }}</label>
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

{{ $driverTrips->links() }}

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
