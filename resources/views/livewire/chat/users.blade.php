<div x-data="usersScope()" x-init="listenForWhisper">
    @if(!empty($users))
        <h3 class="text-base font-plex font-bold">Users Online ({{ count($users) }})</h3>
        @foreach($users as $user)
            <div class="w-full mb-1 border-b">
                <a href="#" class=" text-gray-800">{{ $user['FullName']}}</a>
                <span x-show="isTyping({{ $user['id'] }})" class="text-xs text-gray-400">typing...</span>
            </div>
        @endforeach
    @endif
</div>

@push('scripts')
    <script type="text/javascript">
        function usersScope(){
            return{
                typing: [],

                isTyping(userId){
                    return this.typing[userId] || false
                },

                listenForWhisper () {

                    Echo.private('chat.{{ $roomId }}')
                    .listenForWhisper('typing',(e) => {

                        this.typing[e.id] = e.typing
                    })
                }
            }
        }
    </script>
@endpush