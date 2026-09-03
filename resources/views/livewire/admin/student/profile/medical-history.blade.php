<div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3">
    <div class="flex lg:justify-end w-full px-3">
        <a href="{{ url('/admin/student/add/medicalHistory/'.$name) }}" title="Edit" class="text-blue-600">Edit</a>
    </div>

    <div class="flex flex-wrap custom-table mx-3 my-3">
        <ul class="list-reset leading-loose my-2 text-xs">
            <li class="flex py-1"><span class="text-gray-700 font-medium mx-2">Height :</span><p>{{ $medical['height'] }}</p></li>
            <li class="flex py-1"><span class="text-gray-700 font-medium mx-2">Weight :</span><p>{{ $medical['weight'] }}</p></li>
            <li class="flex py-1"><span class="text-gray-700 font-medium mx-2">Medication Problems :</span><p>{{ $medical['medication_problems'] }}</p></li>
            <li class="flex py-1"><span class="text-gray-700 font-medium mx-2">Medication Needs :</span><p>{{ $medical['medication_needs'] }}</p></li>
            <li class="flex py-1"><span class="text-gray-700 font-medium mx-2">Medication Allergies :</span><p>{{ $medical['medication_allergies'] }}</p></li>
            <li class="flex py-1"><span class="text-gray-700 font-medium mx-2">Food Allergies :</span><p>{{ $medical['food_allergies'] }}</p></li>
            <li class="flex py-1"><span class="text-gray-700 font-medium mx-2">Other Allergies :</span><p>{{ $medical['other_allergies'] }}</p></li>
            <li class="flex py-1"><span class="text-gray-700 font-medium mx-2">Student Personal identification:</span><p>{{ $medical['other_medical_information'] }}</p></li>
        </ul>
    </div>
</div>
