<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin\Student\Profile;

use Livewire\Component;

/**
 * Shell for the Student Profile page's tab strip.
 *
 * Mirrors the old ProfileTab.vue + Teleport pattern, but only mounts the
 * active tab's Livewire component -- so switching tabs queries fresh data
 * instead of every tab eagerly fetching on page load like the Vue version did.
 */
class ProfileTabs extends Component
{
    public const TABS = [
        'overview' => 'admin.student.profile.overview',
        'timeline' => 'admin.student.profile.timeline',
        'family' => 'admin.student.profile.family',
        'siblings' => 'admin.student.profile.siblings',
        'discipline' => 'admin.student.profile.discipline',
        'notes' => 'admin.student.profile.notes',
        'library' => 'admin.student.profile.library-activity',
        'documents' => 'admin.student.profile.documents',
        'attendance' => 'admin.student.profile.attendance',
        'medical' => 'admin.student.profile.medical-history',
        'fees' => 'admin.student.profile.fees',
        'leave' => 'admin.student.profile.leave-history',
        'bank' => 'admin.student.profile.bank-details',
        'tags' => 'admin.student.profile.tags',
    ];

    /**
     * Tabs with a real Livewire component built so far. Others fall back to
     * the still-live Vue teleport target while their conversion is pending
     * (see the phased plan) -- update this list as each tab ships.
     */
    public const IMPLEMENTED_TABS = ['overview', 'timeline', 'family', 'siblings', 'library', 'attendance', 'leave', 'notes', 'discipline', 'medical', 'documents', 'bank', 'fees', 'tags'];

    public string $name;

    public string $activeTab = 'overview';

    public bool $gfeeEnabled = false;

    public function mount(string $name)
    {
        $this->name = $name;
        $this->gfeeEnabled = (bool) config('gfee.enabled', false);
    }

    public function setTab(string $tab): void
    {
        if (array_key_exists($tab, self::TABS)) {
            $this->activeTab = $tab;
        }
    }

    public function render()
    {
        $activeComponent = in_array($this->activeTab, self::IMPLEMENTED_TABS)
            ? self::TABS[$this->activeTab] ?? null
            : null;

        return view('livewire.admin.student.profile.profile-tabs', [
            'activeComponent' => $activeComponent,
        ]);
    }
}
