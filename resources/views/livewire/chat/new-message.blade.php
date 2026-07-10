<div x-data="formScope()" x-init="watchTyping" class="w-full">
    <form action="" wire:submit.prevent="send">
	    <div class="">
	    	<textarea rows="3" class="form-control w-full p-2 cursor-text border" wire:model="body" x-on:keydown="determineTypingState" x-on:keydown.enter="submit"></textarea>
	    	
	    	@error('body') 
                <span class="text-xs text-red-600">{{ $message }}</span> 
         @enderror
	    </div>
    	<button type="submit" class="btn bg-green-600 text-white px-3 py-1 rounded" x-ref="submit">Send</button>
    </form>
</div>

@push('scripts')
	<script type="text/javascript">
		function formScope(){
			let typingTimer
			return{
				//typing: false,
				typing: '',
				submit(e){
					if (e.shiftKey) return      //shift+enter
					this.$refs.submit.click()
				},

				determineTypingState()
				{
					this.typing=true

					clearTimeout(typingTimer)

					typingTimer = setTimeout(() => {
						//this.typing = false
					}, 2000)
				},

				watchTyping()
				{
					setTimeout(() => {
						this.whisperTyping(false)
					},2000)
					this.$watch('typing', (typing) => {
						this.whisperTyping(typing)
					})
				},

				whisperTyping(typing) {

					Echo.private('chat.{{ $room->id}}')
						.whisper('typing', {
						id:User.id,
						typing
					})

				}

			}
		}
	</script>
@endpush