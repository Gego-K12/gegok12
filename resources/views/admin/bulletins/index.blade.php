@extends('layouts.admin.layout')

@section('content')

    <div class="flex flex-row justify-between">
        <div class="w-1/2 ">
            <h1 class="admin-h1 my-3">Magazine ( {{ $count }} )</h1>
        </div>
        <form action="{{ url('/admin/magazines') }}" enctype="multipart form-data">
            <div class="py-3">
                <div class="flex items-center mx-2">
                    <div class="search relative mx-2">
                        <input type="text" name="search" class="border px-10 py-1 text-sm border-gray-400 w-full rounded bg-white shadow" placeholder="Search" value="{{ old('search') }}">  
                        <span class="input-group-btn absolute left-0 px-3 py-2 top-0">                  
                            <button type="submit">
                                <svg class="w-4 h-4 fill-current text-gray-600" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30.239px" height="30.239px" viewBox="0 0 30.239 30.239" style="enable-background:new 0 0 30.239 30.239;" xml:space="preserve"><g><path d="M20.194,3.46c-4.613-4.613-12.121-4.613-16.734,0c-4.612,4.614-4.612,12.121,0,16.735 c4.108,4.107,10.506,4.547,15.116,1.34c0.097,0.459,0.319,0.897,0.676,1.254l6.718,6.718c0.979,0.977,2.561,0.977,3.535,0 c0.978-0.978,0.978-2.56,0-3.535l-6.718-6.72c-0.355-0.354-0.794-0.577-1.253-0.674C24.743,13.967,24.303,7.57,20.194,3.46z M18.073,18.074c-3.444,3.444-9.049,3.444-12.492,0c-3.442-3.444-3.442-9.048,0-12.492c3.443-3.443,9.048-3.443,12.492,0 C21.517,9.026,21.517,14.63,18.073,18.074z"/></g></svg>
                            </button>
                        </span>
                    </div>
                    <div class="date-select date-select_none dashboard-reset mx-1 lg:mx-0 md:mx-0">
                        <a href="{{ url('/admin/magazines') }}" id="do-reset" class="text-sm border bg-gray-100 text-grey-darkest py-1 px-4">Reset</a>
                    </div>
                </div>
            </div>
        </form>
        <div class="relative flex items-center w-3/4  lg:w-1/2 md:w-1/2 justify-end">
            <a href="{{url('/admin/magazine/create')}}" id="upload-btn" class="no-underline text-white  px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">
                <span class="mx-1 text-sm font-semibold">Add</span>
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 409.6 409.6" xml:space="preserve" class="w-3 h-3 fill-current text-white"><g><g><path d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067 C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067 s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"></path></g></g></svg>
            </a> 
        </div>
    </div>
        
    <div class="flex flex-wrap">
      @if(count($bulletins)!=0)
        @foreach($bulletins as $key => $magazine)
            <div class="w-full lg:w-1/4 px-1 my-2">
                <p class="text-sm">Year - {{ $magazine->year }}</p>
                <div class="w-full py-2">
                    <div class="shadow-md p-3 bg-white rounded">
                        <div class="flex">
                            <img class="card-img-top w-16 h-16" src="{{$magazine->ImagePath}}">
                            <div class="px-3 w-10/12">  
                                <p class="font-bold text-base text-gray-700 capitalize">
                                    {{ $magazine->name }}
                                </p>
                                <p class="font-semibold text-sm text-gray-700 capitalize">
                                    Year -{{ $magazine->year }}
                                </p>
                                <p class="font-medium text-xs text-gray-500 capitalize flex items-center py-1">
                                    <a href="{{ $magazine->FilePath }}">Download File</a> 
                                </p>    
                            </div>
                            <div class="flex justify-end">
                    <a href="#" rel="{{url('/admin/magazine/delete/'.$magazine->id)}}" id="remove_magazine" class="left-auto delete-magazine" title="Delete">
                      <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512.001 512.001" xml:space="preserve" class="w-3 h-3 m-1 fill-current text-black-700"><g><g><path d="M284.286,256.002L506.143,34.144c7.811-7.811,7.811-20.475,0-28.285c-7.811-7.81-20.475-7.811-28.285,0L256,227.717 L34.143,5.859c-7.811-7.811-20.475-7.811-28.285,0c-7.81,7.811-7.811,20.475,0,28.285l221.857,221.857L5.858,477.859 c-7.811,7.811-7.811,20.475,0,28.285c3.905,3.905,9.024,5.857,14.143,5.857c5.119,0,10.237-1.952,14.143-5.857L256,284.287 l221.857,221.857c3.905,3.905,9.024,5.857,14.143,5.857s10.237-1.952,14.143-5.857c7.811-7.811,7.811-20.475,0-28.285 L284.286,256.002z"></path></g></g></svg>
                    </a>
                  </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
      @else
        <p class="font-medium text-sm text-gray-600 capitalize flex items-center py-2">No records found</p>
      @endif
    </div>
@endsection

@push('scripts')

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.delete-magazine').on('click', function(){
            var link = $(this).attr('rel');
            var status = $(this).attr('value');
            //alert(status);
            swal({
                icon: "info",
                text: "Do you want to delete this magazine ?",
                buttons: {
                    cancel: true,
                    confirm: true,
                },
                allowOutsideClick: false,
            }).then((willChange) => {
                if (willChange) 
                {
                    $.ajax({
                        url: link,
                        data: { status: status },
                        type: "DELETE",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success:function(data)
                        {
                            //alert(ans);
                            swal({
                                icon: "success",
                                text: "Magazine Deleted Successfully",
                            }).then(function(){
                                window.location.reload();
                            });
                        }
                    })
                } 
                else 
                {
                    swal("Cancelled");
                } 
            });
        });
    });
</script>
@endpush