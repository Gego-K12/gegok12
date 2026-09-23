<div class="relative">
    @if (session()->has('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <div class="flex items-center justify-between my-3">
        <h1 class="admin-h1">Parents</h1>
        <button type="button" wire:click="resetFilters" class="text-sm border bg-gray-100 text-grey-darkest py-2 px-4">Reset</button>
    </div>

    <div class="flex flex-wrap custom-table my-3 overflow-auto">
        <table class="w-full">
            <thead class="bg-grey-light">
                <tr class="border-b">
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Name</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Parent Of</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Mobile Number</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Action</th>
                </tr>
                <tr class="border-b bg-white">
                    <th class="px-2 py-2">
                        <input type="text" wire:model.live.debounce.400ms="name" placeholder="Search" class="tw-form-control w-full text-sm bg-gray-50 text-gray-900">
                    </th>
                    <th class="px-2 py-2">
                        <input type="text" wire:model.live.debounce.400ms="parentOf" placeholder="Search" class="tw-form-control w-full text-sm bg-gray-50 text-gray-900">
                    </th>
                    <th class="px-2 py-2">
                        <input type="text" wire:model.live.debounce.400ms="mobileNo" placeholder="Search" class="tw-form-control w-full text-sm bg-gray-50 text-gray-900">
                    </th>
                    <th class="px-2 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($parents as $parent)
                    <tr class="border-b" wire:key="parent-{{ $parent->id }}">
                        <td class="py-3 px-2">
                            <a href="{{ url('/admin/parent/show/'.$parent->name) }}" class="font-semibold text-sm text-blue-700 hover:underline">
                                {{ $parent->FullName }}
                            </a>
                            @if ($parent->status !== 'active')
                                <span class="ml-2 rounded-full px-2 py-0.5 text-xs font-semibold bg-gray-200 text-gray-600">Inactive</span>
                            @endif
                        </td>
                        <td class="py-3 px-2">
                            <p class="text-sm">{{ $parent->getChildren() ?: '-' }}</p>
                        </td>
                        <td class="py-3 px-2">
                            <p class="text-sm">{{ $parent->mobile_no }}</p>
                        </td>
                        <td class="py-3 px-2">
                            <a href="{{ url('/admin/parent/edit/'.$parent->name) }}" class="inline-block btn px-3 py-1 bg-blue-600 text-white rounded text-sm">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr class="border-b">
                        <td colspan="4">
                            <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $parents->links() }}
    </div>
</div>
