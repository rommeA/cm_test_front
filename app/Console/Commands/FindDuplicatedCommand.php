<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Port;
use App\Models\Seaman;
use App\Models\SeamanPreviousService;
use App\Models\SeamanRank;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Vessel;
use App\Models\VesselFlag;
use App\Models\VesselName;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use MoveMoveIo\DaData\Facades\DaDataAddress;

class FindDuplicatedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seamen:findDuplicates';

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
        $json_seaman = \File::get(storage_path() . '/database/CM/seaman_CM.json');


        $seaman_raw = json_decode($json_seaman)->seaman;

        $this->importSeamen($seaman_raw);

        return Command::SUCCESS;
    }


    public function importSeamen($seaman_raw)
    {

        $this->info('importing seaman');
        $emails = [];
        $duplicates = [];

        $reimport_indexes = [];
        foreach ($seaman_raw as $seaman) {
            $seaman_email = strtolower($seaman->email);
            if ($seaman_email === '') {
                continue;
            }
            if (isset ($emails[$seaman_email])) {
                $type = match ($seaman->type) {
                    "1" => config('enums.employee_type.seaman_applicants'),
                    "2" => config('enums.employee_type.seaman_candidates'),
                    "3" => config('enums.employee_type.seaman_crew'),
                    "4" => config('enums.employee_type.seaman_crew_archive'),
                    "5" => config('enums.employee_type.seaman_precaution'),
                };


                $type_old = match ($emails[$seaman_email]->type) {
                    "1" => config('enums.employee_type.seaman_applicants'),
                    "2" => config('enums.employee_type.seaman_candidates'),
                    "3" => config('enums.employee_type.seaman_crew'),
                    "4" => config('enums.employee_type.seaman_crew_archive'),
                    "5" => config('enums.employee_type.seaman_precaution'),
                };

                if (
                    ($type_old == config('enums.employee_type.seaman_crew') || $type_old == config('enums.employee_type.seaman_applicants'))
                    &&
                    ($type == config('enums.employee_type.seaman_crew_archive'))
                ) {
                    $reimport_indexes[] = [
                        'old_cm_id' => $emails[$seaman_email]->id,
                        'new_cm_id' => $emails[$seaman_email]->id,
                        'old_type' => $type_old,
                        'new_type' => $type_old,
                        'email' => $seaman_email
                    ];
                    continue;
                }

                $reimport_indexes[] = [
                    'old_cm_id' => $emails[$seaman_email]->id,
                    'new_cm_id' => $seaman->id,
                    'old_type' => $type_old,
                    'new_type' => $type,
                    'email' => $seaman_email
                ];

                $duplicates[$seaman_email] = $duplicates[$seaman_email] ?? ($emails[$seaman_email]->id . ' - ' . $type_old )
                    . ', ' . $seaman->id . ' - ' . $type;
            }
            $emails[$seaman_email] = $seaman;
        }


        foreach ($reimport_indexes as $row){
            $user = Seaman::where('email', 'ilike', $row['email'])->first();

            $user->cm_seaman_id = $row['new_cm_id'];
            $user->save();

            $user = Seaman::where('cm_seaman_id', $row['new_cm_id'])->first();

//            $this->info('old id: ' . $row['old_cm_id']
//                . ', new id: ' . $row['new_cm_id']
//                . ', ' . ($user?->displayName ?? $user?->name)
//                .', ' . $user?->employee_type
//                . ', ' . $user?->rank?->name
//                . ', new type: ' . $row['new_type']
//                . ', old type: ' . $row['old_type']
//            );

            $data = $this->getEmployeeData($emails[$row['email']], strtolower($user->email), true, false);
            $created_user = User::where('email', 'ilike', $row['email'])->update($data);

            $address_raw = implode(', ', [
                $user->aaZipCode,
                $user->aaCountry,
                $user->aaRegion,
                $user->aaCity,
                $user->aaStreet,
                $user->aaHouse,
                $user->aaRoom
            ]);

            if ($user->aaCity) {
                UserAddress::updateOrCreate([
                    'user_id' => $created_user->id,
                    'address' => $address_raw
                ]);
            }

        }
        $this->info(count($duplicates));


        $this->photoSeeder($reimport_indexes);
        $this->prevServSeeder($reimport_indexes);

        $this->info('SeamanBeginningOfSeaServiceSeeder');
        Artisan::call('db:seed --class=SeamanBeginningOfSeaServiceSeeder');
        $this->info('SeamanRelativesSeeder');
        Artisan::call('db:seed --class=SeamanRelativesSeeder');
        Artisan::call('db:seed --class=SeamanNotesSeeder');

        $this->documentSeeder($reimport_indexes);


    }


    public function documentSeeder($reimport_indexes)
    {
        $seaman_indexes = [];
        foreach ($reimport_indexes as $row) {
            $seaman_indexes[] = $row['new_cm_id'];
        }
        $json_doctypes = \File::get(storage_path() . '/database/CM/seaman_document_list_CM.json');
        $json_documents = \File::get(storage_path() . '/database/CM/seaman_documents_CM.json');

        $json_seamen = \File::get(storage_path() . '/database/CM/seaman_CM.json');

        $seamen_raw = json_decode($json_seamen)->seaman;

        $seamen = [];
        foreach ($seamen_raw as $item) {
            $seamen[$item->id] = $item;
        }

        $documents = json_decode($json_documents)->data;
        $doctypes = json_decode($json_doctypes)->data;

        $doctype_dict = [];
        foreach ($doctypes as $type) {
            $db_doctype = DocumentType::where('name', $type->title_en)->orWhere('old_name', $type->title_en)->first();
            if (! $db_doctype) {
                $this->info('doctype not found: ' . $type->title_en. '.');
            } else {
                $doctype_dict[$type->id] = $db_doctype;
            }
        }

        foreach ($documents as $document) {
            if ( ! in_array($document->seaman_id, $seaman_indexes)) {
                continue;
            }
            $employee = $seamen[$document->seaman_id] ?? null;
            if ($employee) {
                $user = User::where('email', 'ilike', $employee->email)->first();
                if ($user) {
                    $is_relevant = (bool)(int)$document->relevant;
                    $is_archive = false;
                    if (!$is_relevant and $document->dateValid < now()) {
                        $is_archive = true;
                    }
                    Document::updateOrCreate(
                        [
                            'cm_id' => $document->id
                        ],
                        [
                            'document_type_id' => $doctype_dict[$document->document_id]->id ?? null,
                            'user_id' => $user->id,
                            'number' => $document->number,
                            'date_issue' => $document->dateIssue == '0000-00-00' ? '0001-01-01' : $document->dateIssue,
                            'date_valid' => $document->dateValid,
                            'is_relevant' => $is_relevant,
                            'is_archive' => $is_archive,
                            'place' => $document->place,
                        ]
                    );
                } else {
                    $this->info('seaman not found: ' . strtolower($employee->email));
                }
            } else {
                $this->info('seaman not found: ' . $document->seaman_id);

            }
        }
    }

    public function photoSeeder($reimport_indexes)
    {
        $baseDir_seamen = storage_path() . '/app/public/uploads/CrewPersonnel/Seaman/photo';

        foreach ($reimport_indexes as $row) {
            $user = User::where('email', 'ilike', $row['email'])->first();

            if (!$user) {
                $user = User::where('cm_seaman_id', $row['new_cm_id'])->first();
            }
            try {
                $path = $baseDir_seamen . "/" . $row['new_cm_id'] . ".jpg";
                $logo = \File::get($path);
                $base64 = base64_encode($logo);
                $user->binary_photo = $base64;
                $user->save();
                $this->info('importing photo, seaman id: '. $row['new_cm_id'] .
                    ', user id: ' . $user->id .
                    ', user name: ' . $user->displayName);

            } catch (\Exception $e) {

            }
        }
    }

    public function prevServSeeder($reimport_indexes)
    {

        $this->info('processing company vessels list...');
        $json_vessels = \File::get(storage_path() . '/database/CM/vessels_CM.json');
        $company_vessels_raw = json_decode($json_vessels)->data;
        $company_vessels = [];

        foreach ($company_vessels_raw as $vessel) {
            $company_vessels[$vessel->vessel_id] = $vessel;
        }
        unset($vessel);

        $i = 0;
        foreach ($reimport_indexes as $row) {
            $user = Seaman::where('cm_seaman_id', $row['new_cm_id'])->first();
            if (! $user) {
                continue;
            }
            $user->previousServiceSea()->delete();

            foreach (DB::table('cm_prev_service')->where('seaman_id', $row['new_cm_id'])->get() as $prev_serv) {
                $i++;

                if (DB::table('cm_vessels')->where(['cm_id' => $prev_serv->vessel_id])->count() == 0) {
                    $this->info("pass vessel $prev_serv->vessel_id");
                    continue;
                }

                $cm_vessel = DB::table('cm_vessels')->where(['cm_id' => $prev_serv->vessel_id])->first();

//                $user = User::where('cm_seaman_id', $prev_serv->seaman_id)->first();
//                $this->info('IMO: ' . $cm_vessel->imo . ', ID: ' . $cm_vessel->id);
                $vessel = Vessel::where(($cm_vessel->imo ? 'imo' : 'cm_id'), ($cm_vessel->imo ? $cm_vessel->imo : $cm_vessel->id))->first();
                if (! $user or ! $vessel) {
                    $this->info("pass vessel $prev_serv->vessel_id, user $prev_serv->seaman_id");

                    continue;
                }

                $prev_serv = $this->clearData($prev_serv);

                if (! $prev_serv) {
                    continue;
                }
                $date_from = $prev_serv->dateFrom;
                $date_to = $prev_serv->dateTo;

                $cm_date_from = \DateTime::createFromFormat('Y-m-d', $date_from);
                $cm_date_to = $prev_serv->dateTo ? \DateTime::createFromFormat('Y-m-d', $date_to) : null;

                $cm_date_from = $cm_date_from->format('Y-m-d');
                $cm_date_to = $cm_date_to == null ? null : $cm_date_to->format('Y-m-d');

                if (SeamanPreviousService::where(['cm_id' => $prev_serv->cm_id])->first()) {
                    continue;
                }

                $company_id = isset($company_vessels[$prev_serv->vessel_id])
                    ? Company::select('companies.*')
                        ->join('vessels', 'companies.id', 'vessels.company_id')
                        ->where('companies.cm_id', $company_vessels[$prev_serv->vessel_id]->office_id)
                        ->first()?->id ?? null
                    : null;

                $company_name = $cm_vessel->crewing;


                SeamanPreviousService::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'vessel_id' => $vessel->id,
                        'rank_id' => SeamanRank::where('cm_id', $prev_serv->rank_id)->first()->id,
                        'date_from' => $cm_date_from,
                        'date_to' => $cm_date_to,
                    ],
                    [
                        'company_id' => $company_id,
                        'company_name' => $company_name,
                        'cm_id' => $prev_serv->cm_id,
                    ]
                );

                $cm_title = $cm_vessel->title;
                if ($vessel->name == null or $vessel->name !== $cm_title) {
                    VesselName::create(
                        [
                            'vessel_id' => $vessel->id,
                            'name' => $cm_title,
                            'date_from' => $cm_date_from
                        ]
                    );
                }
                $cm_flag = $cm_vessel->flag;

                if ($vessel->flag == null or $vessel->flag !== $cm_flag) {
                    VesselFlag::create(
                        [
                            'vessel_id' => $vessel->id,
                            'flag' => $cm_flag,
                            'date_from' => $cm_date_from
                        ]
                    );
                }
            }
        }

    }



    public function clearData($prev_serv)
    {
        $dateFrom = $prev_serv->dateFrom;
        $dateTo = $prev_serv->dateTo;
        if ($dateTo == '3013-08-03') {
            $prev_serv->dateTo = '2013-08-03';
        } elseif ($dateTo == '3013-06-12') {
            $prev_serv->dateTo = '2013-06-12';
        } elseif ($dateTo == '1013-05-05') {
            $prev_serv->dateTo = '2013-05-05';
        } elseif ($dateTo == '1015-01-22') {
            $prev_serv->dateTo = '2015-01-22';
        } elseif ($dateTo == '1015-02-11') {
            $prev_serv->dateTo = '2015-02-11';
        } elseif ($dateTo == '1017-02-14') {
            $prev_serv->dateTo = '2017-02-14';
        } elseif ($dateTo == '1501-03-19') {
            return null;
        } elseif ($dateTo == '1914-07-09') {
            $prev_serv->dateTo = '2014-07-09';
        } elseif ($dateTo == '1920-02-16') {
            $prev_serv->dateTo = '2020-02-16';
        } elseif ($dateFrom == '1010-09-26') {
            $prev_serv->dateFrom = '2010-09-26';
        } elseif ($dateFrom == '1013-01-18') {
            $prev_serv->dateFrom = '2013-01-18';
        } elseif ($dateFrom == '1013-07-04') {
            $prev_serv->dateFrom = '2013-07-04';
        } elseif ($dateFrom == '1387-09-07') {
            return null;
        }
        return $prev_serv;
    }


    public function getEmployeeData($employee, $uniqueName, $is_seaman, $is_ldap)
    {
        $is_on_board = null;
        $registration_address_raw = implode(', ', [
            $employee->aorZipCode,
            $employee->aorCountry,
            $employee->aorRegion,
            $employee->aorCity,
            $employee->aorStreet,
            $employee->aorHouse,
            $employee->aorRoom
        ]);


        $application_form_status = null;
        $rank = null;
        $availableDate = null;
        if ($is_seaman) {
            // seaman types: 1 - application, 2 - candidate, 3 - crew, 4 - crew_archive, 5 - blacklist
            $type = match ($employee->type) {
                "1" => config('enums.employee_type.seaman_applicants'),
                "2" => config('enums.employee_type.seaman_candidates'),
                "3" => config('enums.employee_type.seaman_crew'),
                "4" => config('enums.employee_type.seaman_crew_archive'),
                "5" => config('enums.employee_type.seaman_precaution'),
            };
            $rank = SeamanRank::where('cm_id', $employee->rank_id)->first()->id;

            if ($employee->inVesselStatus == '1') {
                $is_on_board = true;
            } elseif ($employee->inVesselStatus == '2') {
                $is_on_board = false;
            }
            if ($employee->type == "1") {
                $application_form_status = config('enums.application_form_status.checking');
            }
            $availableDate = $employee->availableDate ?
                \DateTime::createFromFormat('Y-m-d', $employee->availableDate)->format('Y-m-d H:i:s')
                : null;
        } else {
            // employee types: 1 - employee, 2 - archive, 3 - partner, 4 - blacklist
            $type = match ($employee->type) {
                "1" => config('enums.employee_type.office_employees'),
                "2" => config('enums.employee_type.office_archive'),
                "3" => config('enums.employee_type.partners'),
            };
        }


        $citizenship = match ($employee->nationality_id) {
            "1" => config('enums.citizenship.Russian Federation'),
            "2" => config('enums.employee_type.Ukraine'),
            "3" => config('enums.employee_type.Belarus'),
            "4" => config('enums.employee_type.Venezuela'),
            "5" => config('enums.employee_type.Philippines'),
            "6" => config('enums.employee_type.Nigeria'),
            "7" => config('enums.employee_type.Vietnam'),

        };

        $date_birth = $employee->dateOfBirth ?
            \DateTime::createFromFormat('Y-m-d', $employee->dateOfBirth)->format('Y-m-d H:i:s')
            : null;

        return [
            'email' => strtolower($employee->email),
            'name' => $uniqueName,
            'firstname' => $employee->name_en,
            'lastname' => $employee->surname_en,
            'firstname_ru' => $employee->name_ru,
            'lastname_ru' => $employee->surname_ru,
            'patronymic' => $employee->middleName_ru,
            'patronymic_ru' => $employee->middleName_ru,

            'citizenship' => $citizenship,
            'sex' => $employee->gender == '1' ? 'Male' : 'Female',
            'date_birth' => $date_birth,
            'place_birth_ru' => $employee->placeOfBirth,
            'place_birth' => $is_seaman ? $employee->placeOfBirthULM : $employee->placeOfBirthFP,
            'marital_status' => $employee->relationship == '1' ? 'Not married' : 'Married',
            'height' => (integer)$employee->height ?? null,
            'weight' => (integer)$employee->weight ?? null,
            'shoe_size' => (double)$employee->shoeSize ?? null,
            'jacket_size' => $employee->overallSize ?? null,

            'skype_login' => $employee->skype,
            'phone' => $employee->mobilePhone,
            'password' => base64_encode(random_bytes(32)),
            'is_ldap' => $is_ldap,
            'employee_type' => $type,
            'comment' => $employee->comment,
            'is_seaman' => $is_seaman,
            'registration_address' => $registration_address_raw,
            'homeport_id' => Port::where('cm_id', $employee->homeport_id)->first()->id ?? null,
            'rank_id' => $rank,
            'is_on_board' => $is_on_board,
            'application_form_status' => $application_form_status,
            'available_date' => $availableDate,
        ];
    }

}
