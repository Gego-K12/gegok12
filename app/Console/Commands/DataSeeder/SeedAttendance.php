<?php

namespace App\Console\Commands\DataSeeder;

use Illuminate\Console\Command;
use App\Models\StandardLink;
use App\Models\Attendance;
use Carbon\Carbon;

class SeedAttendance extends Command {
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'gego:seed-attendance';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Seed dummy attendance for the class';

    /**
    * Create a new command instance.
    *
    * @return void
    */

    public function __construct() {
        parent::__construct();
    }

    /**
    * Execute the console command.
    *
    * @return mixed
    */

    public function handle() {
        $stdLink = $this->ask( 'Enter the class id: ' );
        $seedDate = $this->ask( ' Attendance the month & year (mm-yyyy)' );

        $stdLink = StandardLink::find( $stdLink );

        $allStudents = $stdLink->studentAcademic;
        $carbonDate = Carbon::createFromFormat( 'm-Y', $seedDate );

        $carbonDate->startOfMonth();

        for ( $i = 0; $i < 30; $i++ ) {

            $number = rand( 3, 8 );

            $students = $allStudents->random( $number );
            if ( $i == 0 ) {
                $attendanceDate = $carbonDate;
            } else {
                $attendanceDate = $carbonDate->addDay();
            }
            $status = rand(0,1);
            if($status == 0)
            {
                $reason = rand(1,3);
            }
            foreach ( $students as $student ) {
                Attendance::create( [
                    'school_id' => $stdLink->school_id ,
                    'academic_year_id' => $stdLink->academic_year_id,
                    'standardLink_id' => $stdLink->id,
                    'user_id' => $student->user_id,
                    'date' => $attendanceDate->toDateString(),
                    'session' => 'forenoon',
                    'status' => $status,
                    'reason_id' => $reason,
                    'remarks' => 'Test Entry',
                    'recorded_by' => 2
                ] );
                Attendance::create( [
                    'school_id' => $stdLink->school_id ,
                    'academic_year_id' => $stdLink->academic_year_id,
                    'standardLink_id' => $stdLink->id,
                    'user_id' => $student->user_id,
                    'date' => $attendanceDate->toDateString(),
                    'session' => 'afternoon',
                    'status' => $status,
                    'reason_id' => $reason,
                    'remarks' => 'Test Entry',
                    'recorded_by' => 2
                ] );
            }
        }
    }
}
