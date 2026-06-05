<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="bg-white rounded shadow p-4">

    <h2 class="text-lg font-semibold mb-4">
        Group Members
    </h2>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">#</th>
                    <th class="border px-4 py-2 text-left">Name</th>
                    <th class="border px-4 py-2 text-left">Member Type</th>
                    <th class="border px-4 py-2 text-left">Added On</th>
                </tr>
            </thead>

            <tbody>
                @forelse($groups as $index => $member)
                    <tr>
                        <td class="border px-4 py-2">
                            {{ $groups->firstItem() + $index }}
                        </td>

                        <td class="border px-4 py-2">
                            {{ optional($member->userprofile)->firstname }}
                            {{ optional($member->userprofile)->lastname }}
                        </td>

                        <td class="border px-4 py-2">
                            {{ ucfirst($member->member_type) }}
                        </td>

                        <td class="border px-4 py-2">
                            {{ $member->created_at?->format('d-m-Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="border px-4 py-3 text-center text-gray-500">
                            No members found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $groups->links() }}
    </div>

</div>
</div>
