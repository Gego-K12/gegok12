<div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3">
    <div class="custom-table">
        <table class="w-full overflow-x-auto">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Relation</th>
                    <th>Date Of Birth</th>
                    <th>Class</th>
                </tr>
            </thead>
            @if(count($siblings) > 0)
                <tbody>
                    @foreach($siblings as $sibling)
                        <tr>
                            <td><div class="flex items-center"><a href="#" class="mx-2 text-blue-600 hover:text-blue-400">{{ $sibling['fullname'] }}</a></div></td>
                            <td>{{ $sibling['relation'] }}</td>
                            <td>{{ $sibling['date_of_birth'] }}</td>
                            <td>{{ $sibling['standard_section'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
            @if(count($siblingDetails) > 0)
                <tbody>
                    @foreach($siblingDetails as $sibling)
                        <tr>
                            <td><div class="flex items-center"><span class="mx-2">{{ $sibling['fullname'] }}</span></div></td>
                            <td>{{ $sibling['relation'] }}</td>
                            <td>{{ $sibling['date_of_birth'] }}</td>
                            <td>{{ $sibling['standard_section'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
            @if(count($siblings) === 0 && count($siblingDetails) === 0)
                <tbody>
                    <tr>
                        <td colspan="4"><p class="font-semibold text-s" style="text-align: center">No Records Found</p></td>
                    </tr>
                </tbody>
            @endif
        </table>
    </div>
</div>
