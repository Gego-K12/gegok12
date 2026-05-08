<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(UsergroupTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(PlansTableSeeder::class);
        $this->call(MailTemplatesSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(SmsTemplatesTableSeeder::class);
        $this->call(KeywordsTableSeeder::class);
        $this->call(QualificationTableSeeder::class);
        $this->call(AbsentReasonsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(SchoolsTableSeeder::class);  
        $this->call(SchoolDetailsTableSeeder::class);  
        $this->call(AcademicYearsTableSeeder::class);  
        $this->call(SectionsTableSeeder::class);  
        $this->call(StandardsTableSeeder::class); 
        $this->call(SubjectsTableSeeder::class); 
        //$this->call(UsersTableSeeder::class);  //test
        $this->call(UsersSchoolAdminTableSeeder::class);
        $this->call(UsersTeacherTableSeeder::class);  //test
       $this->call(StandardsLinkTableSeeder::class); //test //
        $this->call(UsersStudentTableSeeder::class);  //test

        $this->call(SubscriptionsTableSeeder::class);  //test //

        $this->call(TeacherTableSeeder::class); //test //

        $this->call(BooksCategoryTableSeeder::class); 
        $this->call(BooksTableSeeder::class); 
        // $this->call(AssignmentTableSeeder::class); //test
        //$this->call(StudentAssignmentTableSeeder::class); //test
        $this->call(ScholasticGradesTableSeeder::class); 
        $this->call(NonScholasticGradesTableSeeder::class);
        $this->call(LeaveTypesTableSeeder::class); 
        $this->call(RoleUsersTableSeeder::class);
        $this->call(HolidaySeeder::class);//test //
        //$this->call(LessonPlanTableSeeder::class);//test
        $this->call(PageCategoryTableSeeder::class);

        $this->call(PayCategoryTableSeeder::class);
        $this->call(PayrollItemTableSeeder::class);
        $this->call(TransactionTypeTableSeeder::class);

        $this->call(TrasactionAccountTableSeeder::class);

        // $this->call(HomeworkSeeder::class); //test
        $this->call(NoticeBoardSeeder::class); //test
        $this->call(PayrollSeeder::class); //test
    }
}
