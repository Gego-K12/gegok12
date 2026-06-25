<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}

<div>

<section class="section">

    <div class="bg-white border p-5">

        <form wire:submit.prevent="save">

            <div class="space-y-5">

                {{-- Standard Name --}}

                <div>

                    <label class="block text-sm font-semibold mb-2">

                        Standard <span class="text-red-500">*</span>

                    </label>

                    <input
                        type="text"
                        wire:model="name"
                        placeholder="Enter Standard Name"
                        class="w-full border border-gray-300 rounded px-3 py-2">

                    @error('name')

                    <span class="text-red-500 text-xs">

                        {{ $message }}

                    </span>

                    @enderror

                </div>


                {{-- Order --}}

                <div>

                    <label class="block text-sm font-semibold mb-2">

                        Order

                    </label>

                    <input
                        type="number"
                        wire:model="order"
                        placeholder="Display Order"
                        class="w-full border border-gray-300 rounded px-3 py-2">

                    @error('order')

                    <span class="text-red-500 text-xs">

                        {{ $message }}

                    </span>

                    @enderror    

                </div>


                {{-- Status --}}

                <div>

                    <label class="block text-sm font-semibold mb-2">

                        Status

                    </label>

                    <select
                        wire:model="status"
                        class="w-full border border-gray-300 rounded px-3 py-2">

                        <option value="1">

                            Active

                        </option>

                        <option value="0">

                            Inactive

                        </option>

                    </select>

                    @error('status')

                    <span class="text-red-500 text-xs">

                        {{ $message }}

                    </span>

                    @enderror

                </div>

            </div>


            <div class="mt-8 flex gap-3">

                <button
                    type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">

                    {{ $standardId ? 'Update' : 'Submit' }}

                </button>

                <button
                    type="button"
                    wire:click="resetForm"
                    class="px-6 py-2 bg-gray-300 rounded hover:bg-gray-400">

                    Reset

                </button>

            </div>

        </form>

    </div>

</section>

</div>

</div>
