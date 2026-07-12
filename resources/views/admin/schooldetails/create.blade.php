@extends('layouts.admin.settings-layout')

@section('content')

<div class="w-full mx-2">
    <h1 class="admin-h1 mb-3 flex items-center">
      <a href="{{ url('/admin/schooldetails') }}" title="Back" class="rounded-full bg-gray-300 p-2">

        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124    c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412    c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008    c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg>

      </a>
      <span class="mx-3">School Details</span>
    </h1>
    
    @include('partials.message')
    <form method="POST" action="" enctype="multipart/form-data">
        @csrf

        <create-schooldetail></create-schooldetail> 

        <portal to="school_address">
            <div class="flex flex-col lg:flex-row md:flex-row">
                <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="address" class="tw-form-label">Address<span class="text-red-500">*</span> </label>
                        </div>
                        <div class="w-full lg:w-3/4 my-2 relative">
                            <input type="text" name="address" class="tw-form-control w-full" id="address" value="{{old('address')}}" required> 
                            <span class="absolute m-2 top-0 right-0">
                                <a href="#" onclick="codeAddress(); return false;" dusk="getCords" id="getCords">
                                    <img src="{{asset('/uploads/icons/search.svg')}}" class="w-4 h-4">
                                </a>
                            </span>
                        </div>
                    </div>   
                </div>

                <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div id="map_canvas" class="tw-form-control w-full" style="height: 250px;"></div>
                    </div>
                </div> 

                <!-- <div class="form-group" hidden>
                    <label for="latitude" class="col-md-4 control-label">Latitude</label>
                    <div class="col-md-6">
                        <input id="latitude" type="text" class="tw-form-control w-1/2" name="latitude" value="{{old('latitude')}}"> 
                    </div>
                </div>

                <div class="form-group" hidden>
                    <label for="longitude" class="col-md-4 control-label">Longitude</label>
                    <div class="col-md-6">
                        <input id="longitude" type="text" class="tw-form-control w-1/2" name="longitude" value="{{old('longitude')}}"> 
                    </div>
                </div> -->
            </div>
        </portal>
    </form> 
</div>

@endsection

@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key={{ config('services.google_maps.key') }}"></script>
    <script type="text/javascript">
</script>
@endpush