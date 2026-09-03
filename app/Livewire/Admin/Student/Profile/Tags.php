<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin\Student\Profile;

use App\Models\SpatieTag;
use App\Models\Users\StudentUser;
use Livewire\Component;

class Tags extends Component
{
    public string $name;

    public $studentId;

    public array $selectedTags = [];

    public bool $showModal = false;

    public string $tagName = '';

    public string $newTagName = '';

    public string $successMessage = '';

    public function mount(string $name)
    {
        $this->name = $name;

        $student = StudentUser::where('name', $name)->first();
        $this->studentId = $student->id;
        $this->selectedTags = $student->tags->pluck('tag_name')->values()->all();
    }

    public function toggleTag(string $tagName): void
    {
        if (($index = array_search($tagName, $this->selectedTags, true)) !== false) {
            unset($this->selectedTags[$index]);
            $this->selectedTags = array_values($this->selectedTags);
        } else {
            $this->selectedTags[] = $tagName;
        }
    }

    public function saveTags(): void
    {
        $student = StudentUser::find($this->studentId);
        $student->syncTagsWithType($this->selectedTags, 'student');

        $this->successMessage = 'Tags saved successfully.';
    }

    public function openModal(): void
    {
        $this->tagName = '';
        $this->newTagName = '';
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function submitTag(): void
    {
        $name = trim($this->newTagName) !== '' ? trim($this->newTagName) : $this->tagName;

        if ($name === '') {
            $this->addError('tagName', 'Please select or enter a tag name.');

            return;
        }

        $tag = SpatieTag::findOrCreate($name, 'student');

        $student = StudentUser::find($this->studentId);

        if (! $student->tags()->where('tags.id', $tag->id)->exists()) {
            $student->attachTag($tag);
        }

        if (! in_array($tag->tag_name, $this->selectedTags, true)) {
            $this->selectedTags[] = $tag->tag_name;
        }

        $this->closeModal();
    }

    public function render()
    {
        $tags = SpatieTag::where('type', 'student')->orderBy('order_column')->get();

        return view('livewire.admin.student.profile.tags', ['tags' => $tags]);
    }
}
