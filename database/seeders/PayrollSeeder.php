<?php

namespace Database\Seeders;

use App\Models\Payroll;
use App\Models\PayrollItem;
use App\Models\PayrollTemplate;
use App\Models\PayslipItem;
use App\Models\Salary;
use App\Models\SalaryItem;
use App\Models\School;
use App\Models\TemplateItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class PayrollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (env('APP_ENV') == 'local' || env('APP_ENV') == 'development') {
            $schools = School::where('status', 1)->get();

            foreach ($schools as $school) {
                $admin = User::BySchool($school->id)->ByRole(3)->first();

                $payroll_items = PayrollItem::get();

                PayrollTemplate::factory(10)
                    ->create([
                        'school_id' => $school->id,
                        'created_by' => $admin->id,
                    ])
                    ->each(function ($payroll_template) use ($payroll_items) {
                        foreach ($payroll_items as $payroll_item) {
                            if ($payroll_item->key == 'BA') {
                                TemplateItem::factory()->create([
                                    'template_id' => $payroll_template->id,
                                    'item_id' => $payroll_item->id,
                                    'paycategory_id' => 2,
                                    'category_value' => null,
                                ]);
                            } else {
                                TemplateItem::factory()->create([
                                    'template_id' => $payroll_template->id,
                                    'item_id' => $payroll_item->id,
                                ]);
                            }

                        }

                    });

                $staffs = User::where([['school_id', $school->id], ['usergroup_id', 5]])->get();

                foreach ($staffs as $staff) {
                    $salary = Salary::factory()->create([
                        'school_id' => $school->id,
                        'staff_id' => $staff->id,
                    ]);

                    $template_items = TemplateItem::where([['template_id', $salary->template_id], ['paycategory_id', '!=', 1]])->get();
                    foreach ($template_items as $template_item) {
                        SalaryItem::factory()->create([
                            'salary_id' => $salary->id,
                            'template_item_id' => $template_item->id,
                        ]);
                    }

                    $payroll = Payroll::factory()->create([
                        'school_id' => $school->id,
                        'staff_id' => $staff->id,
                        'salary_id' => $salary->id,
                    ]);

                    $salary_items = SalaryItem::where('salary_id', $salary->id)->get();

                    foreach ($salary_items as $salary_item) {
                        PayslipItem::create([
                            'payroll_id' => $payroll->id,
                            'salary_item_id' => $salary_item->id,
                            'amount' => $salary_item->amount,
                        ]);
                    }
                }
            }
        }
    }
}
