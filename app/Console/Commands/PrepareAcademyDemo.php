<?php

namespace App\Console\Commands;

use App\Services\AcademyContext;
use App\Services\AcademyGroups;
use App\Services\AcademyStudents;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PrepareAcademyDemo extends Command
{
    protected $signature = 'academy:demo-accounts {--school= : Existing academy ID} {--test-only : Limit academy sign-in and core lists to these four accounts} {--reset-passwords : Explicitly rotate only these four demo passwords}';
    protected $description = 'Prepare one connected admin, teacher, student and parent test cohort without deleting existing accounts';

    public function handle(): int
    {
        $school = (int) $this->option('school');
        if (!$school || !DB::table('schools')->where('id', $school)->whereNull('deleted_at')->exists()) { $this->error('Choose an existing academy with --school=ID.'); return self::FAILURE; }
        $emails = config('academy_backend.demo_emails'); $password = Str::random(24); $created = false;
        try {
            $ids = DB::transaction(function () use ($school, $emails, $password, &$created) {
                DB::table('schools')->where('id', $school)->lockForUpdate()->first(); $ids = [];
                foreach ([3 => 'Admin', 5 => 'Teacher', 6 => 'Student', 7 => 'Parent'] as $role => $label) {
                    $email = $emails[$role]; $existing = DB::table('users')->where('email', $email)->first();
                    if ($existing && ((int) $existing->school_id !== $school || (int) $existing->usergroup_id !== $role || $existing->deleted_at)) { throw new \RuntimeException('A reserved demo email belongs to another account. No changes made.'); }
                    if ($existing) {
                        $ids[$role] = $existing->id;
                        if ($this->option('reset-passwords')) { DB::table('users')->where('id', $existing->id)->update(['password' => Hash::make($password), 'updated_at' => now()]); }
                    } else {
                        $created = true;
                        $ids[$role] = DB::table('users')->insertGetId(['school_id' => $school, 'usergroup_id' => $role, 'name' => 'academy-demo-'.Str::uuid(),
                            'email' => $email, 'password' => Hash::make($password), 'status' => 'active', 'created_at' => now(), 'updated_at' => now()]);
                        DB::table('userprofiles')->insert(['school_id' => $school, 'user_id' => $ids[$role], 'usergroup_id' => $role,
                            'firstname' => 'Demo', 'lastname' => $label, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()]);
                    }
                }
                $actor = (object) ['id' => $ids[3], 'school_id' => $school, 'usergroup_id' => 3];
                $students = app(AcademyStudents::class); $students->linkParent($actor, $ids[6], $ids[7]);
                $group = DB::table('groups as g')->join('academy_group_settings as s', 's.group_id', '=', 'g.id')
                    ->where('s.school_id', $school)->where('s.is_demo', 1)->whereNull('g.deleted_at')->first(['g.id']);
                if (!$group) {
                    $record = app(AcademyGroups::class)->create($school, ['group_name' => 'Academy Test Group', 'level' => 1, 'teacher_id' => $ids[5]]);
                    DB::table('academy_group_settings')->insert(['school_id' => $school, 'group_id' => $record->id, 'level' => 1, 'is_demo' => 1,
                        'meeting_days' => json_encode(['Monday', 'Thursday']), 'start_time' => '17:00', 'end_time' => '18:00', 'timezone' => 'Africa/Cairo', 'created_at' => now(), 'updated_at' => now()]);
                    $j = $students->journey($actor, $ids[6]);
                    if (!$j->level) { $students->placement($actor, $ids[6], ['placement_status' => 'waived', 'level' => 1, 'unit' => 1]); }
                    $students->assign($actor, $ids[6], $record->id);
                }
                return $ids;
            });
        } catch (\Throwable $e) { $this->error($e->getMessage()); return self::FAILURE; }
        if ($this->option('test-only')) {
            $path = $this->laravel->environmentFilePath(); $text = is_file($path) ? file_get_contents($path) : '';
            $text = preg_match('/^ACADEMY_TEST_ONLY=.*$/m', $text) ? preg_replace('/^ACADEMY_TEST_ONLY=.*$/m', 'ACADEMY_TEST_ONLY=true', $text) : rtrim($text)."\nACADEMY_TEST_ONLY=true\n";
            file_put_contents($path, $text); $this->call('config:clear');
            $this->info('Test-only mode enabled. Existing accounts/data are retained. Set ACADEMY_TEST_ONLY=false to restore normal access.');
        }
        $this->table(['Role', 'Login email'], array_map(fn ($role) => [$role, $emails[$role]], [3, 5, 6, 7]));
        if ($created || $this->option('reset-passwords')) { $this->line('Password for newly created or explicitly reset demo accounts: '.$password); }
        else { $this->info('Existing demo passwords unchanged. Use --reset-passwords to rotate these four passwords.'); }
        $this->info('Use Level 1 and the demo teacher to find the connected test group. No previous accounts were deleted.');
        return self::SUCCESS;
    }
}
