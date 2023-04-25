<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserContact;
use Illuminate\Console\Command;

class SaveOriginalIDs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cm:import-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $kim = User::where('name', '_old_Vadim.Kim')->first();
        if ($kim) {
            $kim->name = 'Vadim.Kim';
            $kim->email = 'vadim.kim@femco.ru';
            $kim->employee_type = config('enums.employee_type.office_employees');
            $kim->save();
        }

        $shved = User::where('name', '_old_petrov_av@rusgasshelf.ru')->first();
        if ($shved) {
            $shved->name = 'shved-1977@list.ru';
            $shved->email = 'shved-1977@list.ru';
            $shved->employee_type = config('enums.employee_type.seaman_applicants');
            $shved->save();

        }

        $petrov = User::where('name', '_old__old_petrov_av@rusgasshelf.ru')->first();
        if ($petrov) {
            $petrov->name = 'petrov_av@rusgasshelf.ru';
            $petrov->email = 'petrov_av@rusgasshelf.ru';
            $petrov->employee_type = config('enums.employee_type.partners');
            $petrov->save();
        }

        $marsakova = User::where('name', 'marsakova@femco.ru')->first();
        if ($marsakova) {
            $marsakova->name = 'marina.marsakova';
            $marsakova->is_ldap = true;
            $marsakova->save();
        }

        $grin = User::where('name', 'grin@femco.ru')->first();
        if ($grin) {
            $grin->name = 'anna.grin';
            $grin->is_ldap = true;
            $grin->save();
        }

        $marchuk = User::where('name', 'vladimir.marchuk@femco.ru')->first();
        if ($marchuk) {
            $marchuk->name = 'vladimir.marchuk';
            $marchuk->is_ldap = true;
            $marchuk->save();
        }

        $json = \File::get(storage_path() . '/database/CM/employees_CM.json');
        $json_seaman = \File::get(storage_path() . '/database/CM/seaman_CM.json');



        $seaman_raw = json_decode($json_seaman)->seaman;
        $employees_raw = json_decode($json)->employees;
        $cm_users = json_decode($json)->users;


        $employees = [];
        foreach ($employees_raw as $employee) {
            $employees[$employee->id] = $employee;

            $this->info($employee->email);
            $user = User::where('email', strtolower($employee->email))->first();
            if (!$user) {
                continue;
            }
            $user->cm_employee_id = $employee->id;
            $user->created_at = $employee->created_at;
            $user->updated_at = $employee->updated_at;
            $user->save();
        }

        foreach ($seaman_raw as $seaman) {
            $this->info($seaman->email);

            $user = User::where('email', strtolower($seaman->email))->first();

            if (! $user ) {
                $user = UserContact::where('contact', strtolower($seaman->email))->first()?->user;
            }
            if (! $user ) {
                continue;
            }

            $user->cm_seaman_id = $seaman->id;
            $user->created_at = $seaman->created_at;
            $user->updated_at = $seaman->updated_at;
            $user->save();
        }

        $error_users = [];
        foreach ($cm_users as $user_cm) {
            $this->info($user_cm->username);

            $user = User::where('cm_employee_id', $user_cm->employee_id)->first();
            if (! $user) {
                $user = User::where('email', 'ilike', $user_cm->username)->first();
            }
            if(! $user) {
                $user = User::where('name', 'ilike', $user_cm->username)->first();
            }
            if ($user_cm->username == 'yuliya.sinitenkova') {
                $user = User::where('email', 'ilike', 'yuliya.fesenko@femco.ru')->first();
            }
            if(! $user) {
                $user = User::where('comment', 'ilike', $user_cm->username)->first();
            }
            if (! $user ) {
                $user = UserContact::where('contact', strtolower($user_cm->username))->first()?->user;
            }
            if (! $user ) {
                $user = User::where('cm_seaman_id', $user_cm->profile_id)->first();
            }
            if(! $user) {
                $error_users[] = $user_cm->username;
                continue;
            }
            $user->cm_user_id = $user_cm->id;
            $user->save();
        }

        foreach ($error_users as $s) {
            $this->error($s);

        }
        return 0;
    }
}
