<div>
    <ul class="list-reset flex text-xs profile-tab flex-wrap">
        @foreach([
            'overview' => 'Profile',
            'timeline' => 'Timeline',
            'family' => 'Parents / Guardians',
            'siblings' => 'Siblings',
            'discipline' => 'Disciplines',
            'notes' => 'Notes',
            'library' => 'Library Activities',
            'documents' => 'Documents',
            'attendance' => 'Attendances',
            'medical' => 'Medical History',
            'fees' => 'Fees Record',
            'leave' => 'Leave History',
            'bank' => 'Bank Details',
            'tags' => 'Tags',
        ] as $tab => $label)
            @if($tab === 'fees' && ! $gfeeEnabled)
                @continue
            @endif
            <li class="px-2 mx-3 py-2 {{ $activeTab === $tab ? 'active' : '' }}">
                <a href="#" class="text-gray-700 font-medium" wire:click.prevent="setTab('{{ $tab }}')">{{ $label }}</a>
            </li>
        @endforeach
    </ul>

    <div class="px-3 py-3">
        @if($activeComponent)
            @livewire($activeComponent, ['name' => $name], key('profile-tab-'.$activeTab))
        @endif
    </div>
</div>
