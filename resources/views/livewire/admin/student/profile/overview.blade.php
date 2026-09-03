<div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 flex flex-wrap">
    <div>
        <ul class="list-reset leading-loose my-2 text-xs">
            <li class="flex py-1">
                <span class="text-gray-700 font-medium mx-2">Age :</span>
                <p>{{ $details['age'] }}</p>
            </li>
            <li class="flex py-1">
                <span class="text-gray-700 font-medium mx-2">Admission Number :</span>
                <p>{{ $details['registration_number'] }}</p>
            </li>
            <li class="flex py-1">
                <span class="text-gray-700 font-medium mx-2">EMIS Number :</span>
                <p>{{ $details['EMIS_number'] }}</p>
            </li>
            <li class="flex py-1">
                <span class="text-gray-700 font-medium mx-2">Joining Date :</span>
                <p>{{ $details['joining_date'] }}</p>
            </li>
            <li class="flex py-1">
                <span class="text-gray-700 font-medium mx-2">Class :</span>
                <p>{{ $details['class'] }}</p>
            </li>
            <li class="flex py-1">
                <span class="text-gray-700 font-medium mx-2">Roll Number :</span>
                <p>{{ $details['roll_number'] }}</p>
            </li>
            <li class="flex py-1">
                <span class="text-gray-700 font-medium mx-2">ID Card Number :</span>
                <p>{{ $details['id_card_number'] }}</p>
            </li>
            <li class="flex py-1">
                <span class="text-gray-700 font-medium mx-2">Library Card Number :</span>
                <p>{{ $details['librarycard_number'] }}</p>
            </li>
            @if($details['board_registration_number'] !== null)
                <li class="flex py-1">
                    <span class="text-gray-700 font-medium mx-2">Board Registration Number :</span>
                    <p>{{ $details['board_registration_number'] }}</p>
                </li>
            @endif
            <li class="flex py-1">
                <span class="text-gray-700 font-medium mx-2">Transport :</span>
                <p>{{ $details['transport_mode'] }}</p>
            </li>
            @if(in_array($details['transport_mode'], ['Auto', 'Rickshaw', 'Taxi']))
                <li class="flex py-1">
                    <span class="text-gray-700 font-medium mx-2">Driver Name :</span>
                    <p>{{ $details['driver_name'] }}</p>
                </li>
                <li class="flex py-1">
                    <span class="text-gray-700 font-medium mx-2">Driver Number :</span>
                    <p>{{ $details['driver_number'] }}</p>
                </li>
            @endif
        </ul>
    </div>

    <div class="w-full">
        @livewire('custom-fields.show-all-custom-fields', [
            'entityType' => 'student',
            'entityId' => $entityId,
            'schoolId' => $schoolId,
            'bare' => true,
        ])
    </div>
</div>
