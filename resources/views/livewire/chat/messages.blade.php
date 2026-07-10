<div class="flex-grow flex-col-reverse" style="overflow-y: auto">
    <join-chat></join-chat>
    @if($messages->count())
        @foreach($messages as $message)
            <div class="w-full flex flex-col mb-3">
                @if($loop->iteration==1)
                    <div  class="font-bold text-sm text-center border-b py-1 mb-2 ">{{ $message->created_at->toFormattedDateString() }} <br/></div>
                @endif
                <div class="w-full flex text-xs text-gray-700">
                    <span class="mr-2 capitalize font-bold">{{ $message->user->FullName }}</span>
                    <time>{{ date('d-m-Y h:i:s A',strtotime($message->created_at)) }}</time>
                </div>
                <div style="white-space: pre-wrap;">{{ $message->body }}</div>
            </div>
        @endforeach
    @else
        <p>No Chat Records Found</p>
    @endif
</div>