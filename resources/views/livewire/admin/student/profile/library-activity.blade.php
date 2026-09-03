<div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3">
    <div class="custom-table">
        <table class="w-full overflow-x-auto">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Book Name</th>
                    <th>Issue Date</th>
                    <th>Return Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lent as $item)
                    <tr class="{{ $item['bg_class'] }}">
                        <td>{{ $item['category'] }}</td>
                        <td>{{ $item['author'] }}</td>
                        <td>{{ $item['title'] }}</td>
                        <td>{{ $item['issue_date'] }}</td>
                        <td>{{ $item['return_date'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5"><p class="font-semibold text-s" style="text-align: center">No Records Found</p></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
