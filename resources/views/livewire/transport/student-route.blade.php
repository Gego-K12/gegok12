

    <div class="bg-white shadow px-4 py-3">

        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        <div class="">
            <!-- tw-form-group -->
            <div class="flex flex-col lg:flex-row">
                <div class="w-full lg:w-1/3 md:w-8/12">
                    <div class="w-full  lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="class_teacher_id" class="tw-form-label">Select Route<span
                                    class="text-red-500">*</span></label>
                        </div>
                        <div class="w-full lg:w-8/12 md:w-full">
                            <div class="mb-2">
                                <select class="tw-form-control w-full" id="route_id" wire:model.live="route_id" name="route_id">
                                    <option value="">Select Route</option>
                                    @foreach ($routes as $route)
                                        <option value="{{ $route->id }}">{{ $route->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('route_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row">
                <div class="w-full lg:w-1/3 md:w-8/12">
                    <div class="w-full  lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="class_teacher_id" class="tw-form-label">Select Student<span class="text-red-500">*</span></label>
                        </div>
                        <div class="w-full lg:w-8/12 md:w-full">
                            <div class="mb-2">
                                <select class="tw-form-control w-full" id="student_id" wire:model.live="student_id" name="student_id" multiple>
                                    <option value="">Select Student</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->userprofile->firstname }} {{ $student->userprofile->lastname }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-primary submit-btn" wire:click.live="Submits" >Submit</button>
    </div>
    

