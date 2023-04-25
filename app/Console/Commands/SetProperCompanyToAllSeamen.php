<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Database\Query\Builder;

class SetProperCompanyToAllSeamen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seamen:set-proper-company';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set correct company_id for every seaman';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $firstGroup = [1, 2, 4, 5];
        $firstCompany = Company::where('cm_id', 13)->first();
        $secondGroup = [6, 7];
        $secondCompany = Company::where('cm_id', 3)->first();

        User::where(function(Builder $query) {
            $query->where('employee_type', config('enums.employee_type.seaman_crew'))
                ->orWhere('employee_type', config('enums.employee_type.seaman_crew_archive'))
                ->orWhere('employee_type', config('enums.employee_type.seaman_applicants'))
                ->orWhere('employee_type', config('enums.employee_type.seaman_candidates'))
                ->orWhere('employee_type', config('enums.employee_type.seaman_precaution'))
                ->orWhere(function(Builder $query) {
                    $query->where('is_seaman', true)
                        ->where('employee_type', config('enums.employee_type.office_employees'));
                });
            })
            ->whereHas('company', function ($query) use ($firstGroup) {
                $query->whereIn('cm_id', $firstGroup);
            })->update(['company_id' => $firstCompany?->id]);


        User::where(function(Builder $query) {
            $query->where('employee_type', config('enums.employee_type.seaman_crew'))
                ->orWhere('employee_type', config('enums.employee_type.seaman_crew_archive'))
                ->orWhere('employee_type', config('enums.employee_type.seaman_applicants'))
                ->orWhere('employee_type', config('enums.employee_type.seaman_candidates'))
                ->orWhere('employee_type', config('enums.employee_type.seaman_precaution'))
                ->orWhere(function(Builder $query) {
                    $query->where('is_seaman', true)
                        ->where('employee_type', config('enums.employee_type.office_employees'));
                });
            })
            ->whereHas('company', function ($query) use ($secondGroup) {
                $query->whereIn('cm_id', $secondGroup);
            })->update(['company_id' => $secondCompany?->id]);

        return Command::SUCCESS;
    }
}
