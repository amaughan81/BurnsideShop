<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\User;
use App\StaffUser;
use App\StudentUser;
use App\GoogleUser;


class ImportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Users from existing Intranet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line("Import Users started");

        $db_ext = \DB::connection('mysql_external');

        $users = $db_ext->table('users AS u')
            ->leftJoin('student_info AS si', 'u.uid', '=', 'si.uid')
            //->where('u.role', '!=' ,'student')
            ->where('u.deleted','0')
            //->orderBy('u.uid', 'desc')
            ->get();

        $totalUsers = count($users);

        $this->line($totalUsers." Active Users Found");

        $i = 0;

        foreach($users as $user) {
            $i++;
            $newUser = User::create([
                'auth' => $user->auth,
                'sims_id' => $user->sid,
                'username' => $user->username,
                'password' => bcrypt('burnside'),
                'forename' => $user->forename,
                'surname' => $user->surname,
                'role' => (($user->role == 'teacher') ? 'staff' : $user->role),
                'gender' => (($user->upn != "") ? $user->gender : 'M'),
                'active' => $user->active,
                'deleted' => 0,
                'remember_token' => str_random(10),
            ]);

            if($newUser) {
                $this->line($newUser->forename . ' ' . $newUser->surname . "\t\t <info>(User ".$i." / ". $totalUsers." Imported)</info>");

                if($user->gmail != null && $user->gmail_pw != null) {
                    // create google user
                    $newUser->google()
                        ->save(
                            new GoogleUser([
                                'username' => $user->gmail,
                                'password' => $user->gmail_pw
                            ])
                        );
                }

                if($user->role == 'student') {


                    $newUser->student()
                        ->save(
                            new StudentUser([
                                'year_group' => $user->year_group,
                                'form' => $user->form,
                                'dob' => (($user->dob == "0000-00-00") ? Carbon::now()->format("Y-m-d") : $user->dob),
                                'upn' => $user->upn,
                                'adno' => $user->adno,
                                'fsm' => $user->fsm,
                                'gt' => $user->gt,
                                'eal' => $user->eal,
                                'scei' => $user->scei,
                                'fsme' => $user->fsme,
                                'care' => $user->care,
                                'ppi' => $user->ppi,
                                'sen' => $user->sen,
                                'need_type' => $user->need_type,
                                'ccc' => (($user->ccc == null) ? 0 : $user->ccc)
                            ])
                        );
                } else {
                    $newUser->staff()
                        ->save(
                            new StaffUser([
                                'department_id' => $user->dept_id,
                                'job_role' => $user->job_role,
                                'room_name' => $user->room,
                                'telephone' => $user->phone,
                                'email' => $user->email
                            ])
                        );
                }

            }



        }
    }
}
