<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}

    @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
      <section class="section p-6">
        <div class="w-full bg-white shadow-lg rounded-xl border overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b bg-gray-50">
			    <h1 class="admin-h1">Countries</h1>

			    <a href="{{ url('admin/setting/country/create') }}"
			       class="px-4 py-2 bg-green-600 text-white text-sm rounded
			              hover:bg-green-700 flex items-center gap-1">
			        <svg class="w-4 h-4" fill="none" stroke="currentColor"
			             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
			            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
			                  d="M12 4v16m8-8H4"/>
			        </svg>
			        Add Country
			    </a>
			</div>

            <div class="p-6">

                <div class="w-full">
                    @include('partials.message')
                </div>
                <div class="custom-table overflow-auto">
                	<div class="mb-4 flex items-center justify-end">
                        <input
                            type="text"
                            wire:model.live="search"
                            placeholder="Search Countries..."
                            class="border border-gray-300 rounded px-3 py-2 text-sm w-64
                                   focus:outline-none focus:ring focus:border-blue-300"
                        >
                            <button
                                wire:click="$set('search', '')"
                                class="px-3 py-2 bg-gray-200 text-sm rounded hover:bg-gray-300"
                            >
                                Reset
                            </button>
                    </div>
                    <table class="table table-bordered borderTable">
                        <thead class="bg-grey-light">
                            <tr>
                                <th>Name</th>
                                <th>Short Name</th>
                                <th>Tel Prefix</th>
                                <th>Status</th>
                                <th colspan="4" style="text-align: center;">Action</th>    
                            </tr>
                        </thead>
                        @if(count($countries) > 0)
                            @foreach($countries as $country)
                                <tbody>
                                    <td>
                                        {{ ucfirst($country->name) }}
                                    </td>
                                    <td>
                                        {{ $country->short_name }}
                                    </td>
                                    <td>{{ $country->tel_prefix }}</td>                   

                                    <td>
                                         <span class="px-2 py-1 text-xs rounded
										    {{ $country->status == 0 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
										    {{ $country->status == 0 ? 'In-active' : 'Active' }}
										</span>
                                    </td>
                                    <td class="py-3 px-2">
                                        <div class="flex items-center">
										    <a href="{{ route('admin.setting.countries.update', ['id' => $country->id]) }}" title="Edit">
										        <svg class="w-5 h-5 text-gray-700 hover:text-blue-600 mx-1" fill="currentColor"
										             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
										            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z"/>
										            <path d="M20.71 7.04a1.003 1.003 0 0 0 0-1.42l-2.34-2.34a1.003 1.003 0 0 0-1.42 0l-1.83 1.83 3.75 3.75 1.84-1.82z"/>
										        </svg>
										    </a>
										</div>
                                    </td>
                                </tbody>
                            @endforeach
                        @else
                            <tbody>
                                <td colspan="5" style="text-align: center;"> No Records found</td>
                            </tbody>
                        @endif
                    </table>
                    {{ $countries->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
