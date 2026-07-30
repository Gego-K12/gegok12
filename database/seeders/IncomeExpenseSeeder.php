<?php

namespace Database\Seeders;

use Gegok12\IncomeExpense\Models\IncomeExpenseCategory;
use Gegok12\IncomeExpense\Models\IncomeExpenseAccount;
use Gegok12\IncomeExpense\Models\IncomeExpenseTransaction;
use Gegok12\IncomeExpense\Models\ThirdParty;
use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\User;
use App\Helpers\SiteHelper;

class IncomeExpenseSeeder extends Seeder
{
    public function run(): void
    {
        // Get all schools
        $schools = School::all();

        foreach ($schools as $school) {
            // Seed Income Categories
            $incomeCategories = [
                ['name' => 'Donation', 'code' => 'INC_DON', 'description' => 'Income from donations'],
                ['name' => 'Rent Income', 'code' => 'INC_RENT', 'description' => 'Income from property rent'],
                ['name' => 'Library Penalty', 'code' => 'INC_LIB_PEN', 'description' => 'Income from library penalties'],
                ['name' => 'Miscellaneous Income', 'code' => 'INC_MISC', 'description' => 'Other income sources'],
            ];

            foreach ($incomeCategories as $category) {
                IncomeExpenseCategory::firstOrCreate(
                    ['school_id' => $school->id, 'type' => 'income', 'name' => $category['name']],
                    [
                        'code' => $category['code'],
                        'description' => $category['description'],
                        'is_active' => true,
                    ]
                );
            }

            // Seed Expense Categories
            $expenseCategories = [
                ['name' => 'Salary', 'code' => 'EXP_SAL', 'description' => 'Staff salary expenses'],
                ['name' => 'Salary Advance', 'code' => 'EXP_SAL_ADV', 'description' => 'Salary advances to staff'],
                ['name' => 'Vehicle Maintenance', 'code' => 'EXP_VEH', 'description' => 'Vehicle maintenance & repair'],
                ['name' => 'Library Purchase', 'code' => 'EXP_LIB', 'description' => 'Library book & material purchases'],
                ['name' => 'Office/Stationery', 'code' => 'EXP_OFF', 'description' => 'Office supplies & stationery'],
                ['name' => 'Utilities', 'code' => 'EXP_UTIL', 'description' => 'Electricity, water & utilities'],
                ['name' => 'Maintenance & Repairs', 'code' => 'EXP_MAINT', 'description' => 'Building maintenance & repairs'],
                ['name' => 'Miscellaneous Expense', 'code' => 'EXP_MISC', 'description' => 'Other expenses'],
            ];

            foreach ($expenseCategories as $category) {
                IncomeExpenseCategory::firstOrCreate(
                    ['school_id' => $school->id, 'type' => 'expense', 'name' => $category['name']],
                    [
                        'code' => $category['code'],
                        'description' => $category['description'],
                        'is_active' => true,
                    ]
                );
            }

            // Seed Default Accounts
            $accounts = [
                [
                    'name' => 'Main Bank Account',
                    'account_type' => 'bank',
                    'account_number' => 'MAIN001',
                    'bank_name' => 'Primary Bank',
                    'usable_for' => 'both',
                    'opening_balance' => 0,
                ],
                [
                    'name' => 'Petty Cash',
                    'account_type' => 'cash',
                    'account_number' => null,
                    'bank_name' => null,
                    'usable_for' => 'both',
                    'opening_balance' => 0,
                ],
                [
                    'name' => 'Income Bank Account',
                    'account_type' => 'bank',
                    'account_number' => 'INC001',
                    'bank_name' => 'Income Bank',
                    'usable_for' => 'income',
                    'opening_balance' => 0,
                ],
                [
                    'name' => 'Expense Account',
                    'account_type' => 'bank',
                    'account_number' => 'EXP001',
                    'bank_name' => 'Expense Bank',
                    'usable_for' => 'expense',
                    'opening_balance' => 0,
                ],
            ];

            foreach ($accounts as $account) {
                IncomeExpenseAccount::firstOrCreate(
                    ['school_id' => $school->id, 'name' => $account['name']],
                    [
                        'account_type' => $account['account_type'],
                        'account_number' => $account['account_number'],
                        'bank_name' => $account['bank_name'],
                        'usable_for' => $account['usable_for'],
                        'opening_balance' => $account['opening_balance'],
                        'opening_balance_date' => now()->toDateString(),
                        'is_active' => true,
                    ]
                );
            }

            // Seed 3rd Parties
            $thirdParties = [
                ['name' => 'Legal Office', 'contact_person' => 'Mr. Kumar', 'email' => 'legal@office.com', 'phone' => '9876543210', 'category' => 'Professional Services'],
                ['name' => 'Building Owner', 'contact_person' => 'Mrs. Sharma', 'email' => 'owner@building.com', 'phone' => '9876543211', 'category' => 'Landlord'],
                ['name' => 'Petrol Bunk', 'contact_person' => 'Mr. Patel', 'email' => 'petrol@bunk.com', 'phone' => '9876543212', 'category' => 'Vendor'],
                ['name' => 'Office Supplies Co.', 'contact_person' => 'Mr. Singh', 'email' => 'supplies@company.com', 'phone' => '9876543213', 'category' => 'Vendor'],
            ];

            foreach ($thirdParties as $party) {
                ThirdParty::firstOrCreate(
                    ['school_id' => $school->id, 'name' => $party['name']],
                    [
                        'contact_person' => $party['contact_person'],
                        'email' => $party['email'],
                        'phone' => $party['phone'],
                        'category' => $party['category'],
                        'is_active' => true,
                    ]
                );
            }

            // Seed Sample Transactions
            $academicYear = SiteHelper::getAcademicYear($school->id);
            $mainBank = IncomeExpenseAccount::where('school_id', $school->id)->where('name', 'Main Bank Account')->first();
            $pettyCash = IncomeExpenseAccount::where('school_id', $school->id)->where('name', 'Petty Cash')->first();
            $donationCategory = IncomeExpenseCategory::where('school_id', $school->id)->where('name', 'Donation')->first();
            $rentIncomeCategory = IncomeExpenseCategory::where('school_id', $school->id)->where('name', 'Rent Income')->first();
            $salaryCategory = IncomeExpenseCategory::where('school_id', $school->id)->where('name', 'Salary')->first();
            $maintenanceCategory = IncomeExpenseCategory::where('school_id', $school->id)->where('name', 'Maintenance & Repairs')->first();
            $userAdmin = User::where('school_id', $school->id)->first();

            if ($mainBank && $pettyCash && $academicYear && $userAdmin) {
                // Sample Income 1: Donation
                if ($donationCategory) {
                    IncomeExpenseTransaction::firstOrCreate(
                        ['school_id' => $school->id, 'account_id' => $mainBank->id, 'party_name' => 'ABC Foundation', 'transaction_date' => now()->subDays(5)->toDateString()],
                        [
                            'academic_year_id' => $academicYear->id,
                            'type' => 'income',
                            'category_id' => $donationCategory->id,
                            'amount' => 50000,
                            'payment_method' => 'bank_transfer',
                            'reference_number' => 'REF001',
                            'description' => 'Donation from ABC Foundation',
                            'created_by' => $userAdmin->id,
                            'updated_by' => $userAdmin->id,
                        ]
                    );
                }

                // Sample Income 2: Rent Income
                if ($rentIncomeCategory) {
                    IncomeExpenseTransaction::firstOrCreate(
                        ['school_id' => $school->id, 'account_id' => $mainBank->id, 'party_name' => 'Building Owner', 'transaction_date' => now()->subDays(3)->toDateString()],
                        [
                            'academic_year_id' => $academicYear->id,
                            'type' => 'income',
                            'category_id' => $rentIncomeCategory->id,
                            'amount' => 25000,
                            'payment_method' => 'bank_transfer',
                            'reference_number' => 'REF002',
                            'description' => 'Monthly rent from building',
                            'created_by' => $userAdmin->id,
                            'updated_by' => $userAdmin->id,
                        ]
                    );
                }

                // Sample Expense 1: Salary
                if ($salaryCategory) {
                    IncomeExpenseTransaction::firstOrCreate(
                        ['school_id' => $school->id, 'account_id' => $mainBank->id, 'party_name' => 'Staff Member', 'transaction_date' => now()->subDays(2)->toDateString()],
                        [
                            'academic_year_id' => $academicYear->id,
                            'type' => 'expense',
                            'category_id' => $salaryCategory->id,
                            'amount' => 30000,
                            'payment_method' => 'bank_transfer',
                            'reference_number' => 'SAL001',
                            'description' => 'Monthly salary payment',
                            'created_by' => $userAdmin->id,
                            'updated_by' => $userAdmin->id,
                        ]
                    );
                }

                // Sample Expense 2: Maintenance
                if ($maintenanceCategory) {
                    IncomeExpenseTransaction::firstOrCreate(
                        ['school_id' => $school->id, 'account_id' => $pettyCash->id, 'party_name' => 'Maintenance Contractor', 'transaction_date' => now()->subDays(1)->toDateString()],
                        [
                            'academic_year_id' => $academicYear->id,
                            'type' => 'expense',
                            'category_id' => $maintenanceCategory->id,
                            'amount' => 5000,
                            'payment_method' => 'cash',
                            'reference_number' => 'MAINT001',
                            'description' => 'Building maintenance work',
                            'created_by' => $userAdmin->id,
                            'updated_by' => $userAdmin->id,
                        ]
                    );
                }

                // Sample Transfer: Bank to Cash
                IncomeExpenseTransaction::firstOrCreate(
                    ['school_id' => $school->id, 'account_id' => $mainBank->id, 'to_account_id' => $pettyCash->id, 'transaction_date' => now()->toDateString(), 'type' => 'transfer'],
                    [
                        'academic_year_id' => $academicYear->id,
                        'amount' => 10000,
                        'payment_method' => 'cash',
                        'description' => 'Cash withdrawal from main bank',
                        'created_by' => $userAdmin->id,
                        'updated_by' => $userAdmin->id,
                    ]
                );
            }
        }

        echo "\nIncome & Expense data seeded successfully!\n";
    }
}
