<?php

namespace App\Livewire\Superadmin\Academics;

use App\Models\School;
use App\Models\User;
use App\Models\Usergroup;
use App\Models\Userprofile;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Rule;
use Livewire\Component;

class AdminForm extends Component
{
    use LivewireAlert;

    #[Rule('required')]
    public $usergroup;

    #[Rule('required')]
    public $name;

    #[Rule('required|email')]
    public $email;

    #[Rule('required|numeric|digits:10')]
    public $mobile;

    public $password;

    public $confirm_password;

    public $school_id;

    // public $school_name;

    public $adminId;

    public $segment;

    public $admin;

    public function mount($id)
    {
        $this->school_id = $id;

        $this->adminId = $id;

        $this->segment = \Request::segment('5');

        // if($this->adminId != '')
        if (\Request::segment('5') == 'update') {
            $this->admin = User::where('id', $this->adminId)->first();
            $this->school_id = $this->admin->school_id;
            $this->usergroup = $this->admin->usergroup_id;
            $this->name = $this->admin->name;
            $this->email = $this->admin->email;
            $this->mobile = $this->admin->mobile_no;
        }
    }

    public function submitAdmin()
    {

        $this->validate();

        $data = [
            'school_id' => $this->school_id,
            'usergroup_id' => $this->usergroup,
            'name' => $this->name,
            'email' => $this->email,
            'mobile_no' => $this->mobile,
            'password' => Hash::make($this->password ?? ''),
        ];

        // if($this->adminId == '')
        // if(\Request::segment ('5') == 'create')
        if ($this->segment == 'create') {

            $validatedData = $this->validate([
                'email' => 'required|unique:'.School::class,
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password',
            ]);

            $adminUser = User::create($data);

            Userprofile::create([
                'school_id' => $this->school_id,
                'user_id' => $adminUser->id,
                'usergroup_id' => 3,
                'firstname' => $this->name,
                'lastname' => 'school',
                'profession' => 'admin',
                // 'avatar'        =>  'uploads/male.png',
            ]);

            $this->alert('success', 'Admin created successfully');
        } else {
            $adminUser = User::where('id', $this->adminId)->update($data);

            $this->alert('success', 'Admin updated successfully');
        }

        return redirect(url('superadmin/academics/school/detail/'.$this->school_id));
        // return redirect(url('superadmin/academics/schools'));
    }

    public function render()
    {

        $usergroups = Usergroup::get();

        $schoolDetail = School::where('id', $this->school_id)->first();

        return view('livewire.superadmin.academics.admin-form', [
            'usergroups' => $usergroups,
            'schoolDetail' => $schoolDetail,
            'admin' => $this->admin,
        ]);
    }
}
