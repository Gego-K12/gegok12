<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin\Student\Profile;

use App\Models\TransactionAccount;
use App\Models\User;
use Livewire\Component;

class BankDetails extends Component
{
    public string $name;

    public $studentId;

    public $schoolId;

    public bool $showModal = false;

    public $editingId = null;

    public string $bank_name = '';

    public string $key = '';

    public string $account_number = '';

    public string $ifsc_code = '';

    public function mount(string $name)
    {
        $this->name = $name;

        $student = User::where('name', $name)->first();
        $this->studentId = $student->id;
        $this->schoolId = $student->school_id;
    }

    protected function rules(): array
    {
        return [
            'bank_name' => 'required',
            'key' => 'required',
            'account_number' => 'required|numeric',
            'ifsc_code' => 'required',
        ];
    }

    protected function messages(): array
    {
        return [
            'bank_name.required' => 'Enter Bank Name',
            'key.required' => 'Enter key',
            'account_number.required' => 'Enter Account number',
            'account_number.numeric' => 'Enter Account number',
            'ifsc_code.required' => 'Enter valid IFSC code',
        ];
    }

    public function openAddModal(): void
    {
        $this->reset('bank_name', 'key', 'account_number', 'ifsc_code', 'editingId');
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $account = TransactionAccount::find($id);

        $this->bank_name = $account->name;
        $this->key = $account->key;
        $this->account_number = $account->account_number;
        $this->ifsc_code = $account->ifsc_code;
        $this->editingId = $id;
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function save(): void
    {
        $this->validate();

        $account = $this->editingId !== null ? TransactionAccount::find($this->editingId) : new TransactionAccount;

        if ($this->editingId === null) {
            $account->school_id = $this->schoolId;
            $account->user_id = $this->studentId;
        }

        $account->name = $this->bank_name;
        $account->key = $this->key;
        $account->account_number = $this->account_number;
        $account->ifsc_code = $this->ifsc_code;
        $account->save();

        $this->closeModal();
        $this->reset('bank_name', 'key', 'account_number', 'ifsc_code', 'editingId');
    }

    public function render()
    {
        $account = TransactionAccount::where('user_id', $this->studentId)->first();

        return view('livewire.admin.student.profile.bank-details', ['account' => $account]);
    }
}
