<div class="w-full flex mb-8" style="max-height:calc(100vh - 160px)">
    <div class="w-1/4 flex flex-col p-2 bg-gray-100 border" style="min-width:280px;max-width:320px;overflow-y: auto;">
        <h1 class="admin-h1 mb-3 flex items-center">Group Members ({{ $memberCount }})</h1>
        <div x-data="{ selectedTab: 'second'}" class="flex flex-col">
            <div class="w-full flex border-b-2">
                <div x-on:click="selectedTab = 'first'" class="block px-3 py-2 border border-b-0 rounded-t   border-gray-400 cursor-pointer hover:bg-gray-500 hover:text-white" x-bind:class="{ 'bg-gray-500 text-white font-bold' : selectedTab === 'first'}">Online Users</div>
                <div x-on:click="selectedTab = 'second'" x-bind:class="{ 'bg-gray-500 text-white font-bold' : selectedTab === 'second'}" class="mx-1 block px-3 py-2 border border-b-0 rounded-t  border-gray-400 cursor-pointer hover:bg-gray-500 hover:text-white">All Users</div>
            </div>

            <div x-show="selectedTab === 'first'" class="py-2 border-b border-gray-400">
                <livewire:chat.users :room="$room" />
            </div>
            <div x-show="selectedTab === 'second'" class="flex flex-col" style="overflow-y: auto">
                @foreach($roomlinks as $roomlink)
                    <div class="w-full h-full my-2 px-1" style="overflow-y: auto">
                        <div class="flex py-2 align-top justify-between">
                            <div class="flex">
                                <img src="{{ asset($roomlink->user->userprofile->AvatarPath) }}" class="w-16 h-16">
                                <div class="px-3">
                                    <a href="#">
                                        <span class="font-bold" >{{ $roomlink->user->FullName }}</span>      
                                    </a>
                                    <div class="text-xs">{{ $roomlink->user->email}}</div>
                                    <div class="text-sm">{{ $roomlink->user->usergroup->name }}</div>
                                </div>
                            </div>
                            @if(\Auth::user()->usergroup_id == 3)
                                <div class="flex">
                                    <a href="#" rel="{{ url('/admin/room/removeMember/'.$roomlink->id) }}" id="remove_member" class="left-auto delete-member">
                                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512.001 512.001" xml:space="preserve" class="w-2 h-2 m-1 fill-current text-gray-700"><g><g><path d="M284.286,256.002L506.143,34.144c7.811-7.811,7.811-20.475,0-28.285c-7.811-7.81-20.475-7.811-28.285,0L256,227.717 L34.143,5.859c-7.811-7.811-20.475-7.811-28.285,0c-7.81,7.811-7.811,20.475,0,28.285l221.857,221.857L5.858,477.859 c-7.811,7.811-7.811,20.475,0,28.285c3.905,3.905,9.024,5.857,14.143,5.857c5.119,0,10.237-1.952,14.143-5.857L256,284.287 l221.857,221.857c3.905,3.905,9.024,5.857,14.143,5.857s10.237-1.952,14.143-5.857c7.811-7.811,7.811-20.475,0-28.285 L284.286,256.002z"></path></g></g></svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    {{-- Main chat Area --}}
    <div class="w=3/4 bg-white flex-grow flex p-4 border" style="overflow-y: auto">
        <div class="flex flex-col w-full" style="overflow-y: auto">
            <livewire:chat.messages :room="$room" :messages=$messages />
            {{-- @include('livewire.chat.messages') --}}
            <livewire:chat.new-message :room=$room />
        </div>
    </div>
</div>