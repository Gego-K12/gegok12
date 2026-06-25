<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div>

    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <section class="section">

        <div class="w-full">

            <div class="flex items-center justify-between">

                <h1 class="admin-h1 my-3">Standards</h1>

                <a href="{{ url('admin/setting/standard/create') }}"
                   class="px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700 flex items-center gap-1">

                    <svg class="w-4 h-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 4v16m8-8H4"/>

                    </svg>

                    Add Standard

                </a>

            </div>

            <div class="p-4 bg-white shadow-lg">

                <div class="w-full">
                    @include('partials.message')
                </div>

                <div class="custom-table overflow-auto">

                    {{-- <div class="mb-4 flex items-center justify-end gap-2">

                        <input
                            type="text"
                            wire:model.live="search"
                            placeholder="Search standard..."
                            class="border border-gray-300 rounded px-3 py-2 text-sm w-64 focus:outline-none focus:ring focus:border-blue-300"
                        >

                        <button
                            wire:click="$set('search','')"
                            class="px-3 py-2 bg-gray-200 text-sm rounded hover:bg-gray-300">

                            Reset

                        </button>

                    </div> --}}

                    <table class="table table-bordered borderTable">

                        <thead class="bg-grey-light">

                        <tr>

                            <th>Display Name</th>

                            <th>Name</th>

                            <th>Order</th>

                            <th>Status</th>

                            <th style="text-align:center">
                                Action
                            </th>

                        </tr>

                        </thead>

                        @if(count($standards) > 0)

                            @foreach($standards as $standard)

                                <tbody>

                                <tr>

                                    <td>
                                        {{ $standard->StandardName }}
                                    </td>

                                    <td>
                                        {{ $standard->name }}
                                    </td>

                                    <td>
                                        {{ $standard->order }}
                                    </td>

                                    <td>

                                        <span class="px-2 py-1 text-xs rounded
                                        {{ $standard->status == 0
                                            ? 'bg-red-100 text-red-700'
                                            : 'bg-green-100 text-green-700' }}">

                                            {{ $standard->status == 0
                                                ? 'Inactive'
                                                : 'Active' }}

                                        </span>

                                    </td>

                                    <td>

                                        <div class="flex items-center justify-center">

                                            <a href="{{ route('admin.setting.standards.update',['id'=>$standard->id]) }}"
                                               title="Edit">

                                                <svg class="w-5 h-5 text-gray-700 hover:text-blue-600 mx-1"
                                                     fill="currentColor"
                                                     viewBox="0 0 24 24">

                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z"/>

                                                    <path d="M20.71 7.04a1.003 1.003 0 0 0 0-1.42l-2.34-2.34a1.003 1.003 0 0 0-1.42 0l-1.83 1.83 3.75 3.75 1.84-1.82z"/>

                                                </svg>

                                            </a>

                                        </div>

                                    </td>

                                </tr>

                                </tbody>

                            @endforeach

                        @else

                            <tbody>

                            <tr>

                                <td colspan="5" style="text-align:center">

                                    No Records Found

                                </td>

                            </tr>

                            </tbody>

                        @endif

                    </table>

                    {{ $standards->links() }}

                </div>

            </div>

        </div>

    </section>

</div>
</div>
