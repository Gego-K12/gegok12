<div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3">
    <div class="custom-table my-3">
        <table class="w-full overflow-x-auto">
            <thead>
                <tr>
                    <th>Family Members</th>
                    <th>Relation</th>
                    <th>Contact Number</th>
                    <th>Status</th>
                    <th>Email</th>
                    <th>Occupation</th>
                </tr>
            </thead>
            <tbody>
                @forelse($parents as $parent)
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <a href="{{ url('/admin/parent/show/'.$parent['name']) }}" class="mx-2 text-blue-600 hover:text-blue-400">{{ $parent['fullname'] }}</a>
                            </div>
                        </td>
                        <td>{{ $parent['relation'] }}</td>
                        <td>{{ $parent['mobile_no'] }}</td>
                        <td>
                            @if($parent['status'] == 'active')
                                <span class="bg-green-500 rounded px-1 text-xs text-white">Active</span>
                            @else
                                <span class="bg-red-500 rounded px-1 text-xs text-white">InActive</span>
                            @endif
                        </td>
                        <td><a href="#" class="text-blue-600 hover:text-blue-400">{{ $parent['email'] }}</a></td>
                        <td>
                            <a href="#" class="text-blue-600 hover:text-blue-400">
                                {{ $parent['profession'] }}
                                @if($parent['sub_occupation'] !== '')
                                    ({{ $parent['sub_occupation'] }})
                                @endif
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6"><p class="font-semibold text-s" style="text-align: center">No Records Found</p></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
