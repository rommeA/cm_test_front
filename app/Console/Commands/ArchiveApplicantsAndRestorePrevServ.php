<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Note;
use App\Models\Port;
use App\Models\Reference;
use App\Models\RelativeType;
use App\Models\Seaman;
use App\Models\SeamanPreviousService;
use App\Models\SeamanRank;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserBankCard;
use App\Models\UserRelative;
use App\Models\Vessel;
use App\Models\VesselFlag;
use App\Models\VesselName;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class ArchiveApplicantsAndRestorePrevServ extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'applicants-archive:restore-prev-serv';

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
        $applicants = Seaman::select('users.*')
            ->where('employee_type', config('enums.employee_type.seaman_applicants'))
            ->join('seaman_previous_services', 'seaman_previous_services.user_id', '=', 'users.id')
            ->where('seaman_previous_services.deleted_at', '<>', null)
            ->where('seaman_previous_services.company_id', '<>', null)
            ->groupBy('users.id')
            ->get();

        $this->info(count($applicants));
        $this->importRelatives();
        $this->importReferences();
        $this->importBasicInfo();
        $this->importSeamenCards();
        $this->importPrevServ();
        $this->importDocs();
        $this->importBeginningSea();
        $this->importNotes();

        return Command::SUCCESS;
    }


    public function importBasicInfo()
    {
        $json_seaman = \File::get(storage_path() . '/database/extraData/seaman.json');
        $seaman_raw = json_decode($json_seaman)->seaman;
        $arr = $this->importSeamen($seaman_raw);
        $this->info('created ' . $arr[0] . ' seaman, updated ' . $arr[1] . ' seamen');
    }

    public function importSeamen($seaman_raw)
    {

        $updated = 0;
        $counter = 0;
        $this->info('importing seaman');
        foreach ($seaman_raw as $seaman) {
            $data = $this->getEmployeeData($seaman, strtolower($seaman->email), true, false);
            $address_raw = implode(', ', [
                $seaman->aaZipCode,
                $seaman->aaCountry,
                $seaman->aaRegion,
                $seaman->aaCity,
                $seaman->aaStreet,
                $seaman->aaHouse,
                $seaman->aaRoom
            ]);
            $employee =  Seaman::where('cm_seaman_id', $seaman->id)->first();
            $this->info('updating: ' . $employee?->firstname);
            Seaman::where('cm_seaman_id', $seaman->id)->update($data);
            if ($seaman->aaCity) {
                UserAddress::create([
                    'user_id' => $employee->id,
                    'address' => $address_raw
                ]);
            }
            $updated++;
        }
        return [$counter, $updated];
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

    public function importSeamenCards()
    {
        $json_cards = \File::get(storage_path() . '/database/extraData/seaman_bank_cards.json');


        $bank_cards = json_decode($json_cards)->data;

        foreach ($bank_cards as $card) {
            $user = Seaman::where('cm_seaman_id', '=', $card->seaman_id)->first();

            if ($user) {
                UserBankCard::updateOrcreate([
                    'user_id' => $user->id,
                    'company_id' => Company::where('cm_id', $card->office_id)->first()?->id ?? null,
                    'number' => $card->number,
                    'date_issue' => $card->dateIssue ?? null,
                    'date_valid' => $card->dateValid ?? null,
                    'comment' => $card->comment
                ]);
            }
        }
    }

    public function importPrevServ()
    {
        $this->info('processing company vessels list...');
        $json_vessels = \File::get(storage_path() . '/database/CM/vessels_CM.json');
        $company_vessels_raw = json_decode($json_vessels)->data;
        $company_vessels = [];

        foreach ($company_vessels_raw as $vessel) {
            $company_vessels[$vessel->vessel_id] = $vessel;
        }
        unset($vessel);

        $json_ps = \File::get(storage_path() . '/database/extraData/seaman_prev_serv.json');
        $ps_raw = json_decode($json_ps)->data;
        $i = 0;
        foreach ($ps_raw as $prev_serv) {
            $i++;

            if (DB::table('cm_vessels')->where(['cm_id' => $prev_serv->vessel_id])->count() == 0) {
                $this->info("pass vessel $prev_serv->vessel_id");
                continue;
            }
            $cm_vessel = DB::table('cm_vessels')->where(['cm_id' => $prev_serv->vessel_id])->first();

            $user = Seaman::where('cm_seaman_id', $prev_serv->seaman_id)->first();
            $this->info('IMO: ' . $cm_vessel->imo . ', ID: ' . $cm_vessel->id);
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

            if (SeamanPreviousService::where(['cm_id' => $prev_serv->id])->first()) {
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
                    'cm_id' => $prev_serv->id,
                ]
            );
        }
    }

    public function importNotes()
    {
        $json_notes = \File::get(storage_path() . '/database/extraData/seaman_notes.json');
        $notes = json_decode($json_notes)->data;

        foreach ($notes as $note) {

            $seaman = User::where('cm_seaman_id', $note->seaman_id)->first();
            $creator = User::where('cm_user_id', $note->user_id)->first();

            if ($seaman === null) {
                continue;
            }

            Note::updateOrCreate(
                [
                    'creator_id' => $creator?->id,
                    'seaman_id' => $seaman->id,
                    'text' => $note->text,
                    'attention' => $note->attention,
                    'created_at' =>
                        $note->created_at
                            ? Carbon::createFromTimestamp($note->created_at)->format('Y-m-d H:i:s')
                            : null,
                    'updated_at' =>
                        $note->updated_at
                            ? Carbon::createFromTimestamp($note->updated_at)->format('Y-m-d H:i:s')
                            : null,
                ]
            );
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

    public function importDocs()
    {
        $json_doctypes = \File::get(storage_path() . '/database/CM/seaman_document_list_CM.json');
        $json_documents = \File::get(storage_path() . '/database/extraData/seaman_documents.json');

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
            $user = Seaman::where('cm_seaman_id', '=', $document->seaman_id)->first();

            if ($user) {
                $is_relevant = (bool)(int)$document->relevant;
                $is_archive = false;
                if (!$is_relevant and $document->dateValid < now()) {
                    $is_archive = true;
                }
                Document::updateOrCreate(
                    [
                        'document_type_id' => $doctype_dict[$document->document_id]->id ?? null,
                        'user_id' => $user->id,
                        'number' => $document->number ?? '-',
                        'date_issue' => $document->dateIssue == '0000-00-00' ? '0001-01-01' : $document->dateIssue,
                        'date_valid' => $document->dateValid,
                        'is_relevant' => $is_relevant,
                        'is_archive' => $is_archive,
                        'place' => $document->place,
                        'cm_id' => $document->id
                    ]
                );
            } else {
                $this->info('seaman not found: ' . $document->seaman_id);
            }
        }
    }

    public function importBeginningSea()
    {
        $json = \File::get(storage_path() . '/database/extraData/seaman_education_info.json');
        $data = json_decode($json)->data;

        foreach ($data as $row) {
            Seaman::where('cm_seaman_id', $row->seaman_id)->update([
                'date_beginning_sea_service' => $row->date ? \DateTime::createFromFormat('Y-m-d', $row->date)
                    ->format('Y-m-d') : null,
                'company_beginning_sea_service' => $row->company
            ]);
        }
    }


    public function importRelatives()
    {
        $json_seaman = \File::get(storage_path() . '/database/CM/seaman_relatives_CM.json');
        $relatives_CM = json_decode($json_seaman)->data;

        foreach ($relatives_CM as $relative_cm) {

            $user = Seaman::where('cm_seaman_id', $relative_cm->seaman_id)->first();
            if (! $user or (int)$relative_cm->relationship < 1) {
                continue;
            }
            $user_id = $user->id;

            $relative = UserRelative::updateOrCreate(
                [
                    'user_id' => $user_id,
                    'full_name' => $relative_cm->fullName,
                    'cm_id' => $relative_cm->id,
                    'relative_type_id' => RelativeType::where('number', (int)$relative_cm->relationship)->first()->id,
                    'zip_code' => (int)$relative_cm->zipCode,
                    'country' => $relative_cm->country,
                    'region' => $relative_cm->region,
                    'city' => $relative_cm->city,
                    'street' => $relative_cm->street,
                    'building' => $relative_cm->house,
                    'apartment' => $relative_cm->room,
                    'home_phone' => $relative_cm->homePhone,
                    'mobile_phone' => $relative_cm->mobilePhone,
                    'email' => $relative_cm->email
                ]
            );

            if ($relative_cm->number and $relative_cm->number !== '' and $relative_cm->number !== '0') {
                $relative->update(
                    [
                        'is_beneficiary' => true,
                        'passport_series' => $relative_cm->series,
                        'passport_number' => $relative_cm->number,
                        'passport_place' => $relative_cm->issued,
                        'passport_date_issue' => $relative_cm->whenIssued
                    ]
                );
            }
        }
    }

    public function importReferences()
    {
        $json = \File::get(storage_path() . '/database/extraData/seaman_references.json');
        $refs = json_decode($json)->data;


        foreach ($refs as $ref) {
            $seaman = Seaman::where('cm_seaman_id', $ref->seaman_id)->first();
            if (!$seaman) {
                continue;
            }

            Reference::updateOrCreate(
                [
                    'user_id' => $seaman->id,
                    'vessel' => $ref->vessel,
                    'crewing' => $ref->crewing,
                    'contact' => $ref->contact,
                    'name' => $ref->surname,
                    'comment' => $ref->comment
                ]
            );
        }
    }
}
