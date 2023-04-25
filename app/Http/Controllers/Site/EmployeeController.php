<?php

namespace App\Http\Controllers\Site;

use App\Enums\ContactType;
use App\Enums\ContractorType;
use App\Enums\DocumentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Site\Employee\UserCreateRequest;
use App\Http\Requests\Site\Employee\UserUpdateRequest;
use App\Models\CommerceField;
use App\Models\Company;
use App\Models\Contractor;
use App\Models\Country;
use App\Models\DocumentCategory;
use App\Models\InfoPartner;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

class EmployeeController extends Controller
{
    use DatatableAjaxHandler;

    public function index(): Response
    {
        $this->authorize('viewAny', User::class);

        return response(view('employees.index'), 200);
    }

    public function indexArchive(): Response
    {
        $this->authorize('viewAny', User::class);

        return response(view('employees.archive.index'), 200);
    }

    public function indexCandidates(): Response
    {
        $this->authorize('viewAny', User::class);

        return response(view('employees.candidates.index'), 200);
    }

    public function getArchiveList(Request $request): JsonResponse
    {
        $request->merge(['show-archived' => true]);
        $response = $this->getDatatableAjaxResponse($request);

        return response()->json($response);
    }

    public function getList(Request $request): JsonResponse
    {
        $response = $this->getDatatableAjaxResponse($request);

        return response()->json($response);
    }

    public function getCandidates(Request $request): JsonResponse
    {
        $response = $this->getDatatableAjaxResponse($request, config('enums.employee_type.office_candidates'));

        return response()->json($response);
    }

    public function getDefaultEmployeeType()
    {
        return config('enums.employee_type.office_employees');
    }

    public function getArchiveEmployeeType()
    {
        return config('enums.employee_type.office_archive');
    }

    public function datatableApplyFilters($query, $columnName_arr)
    {
        $filterDepartment = substr($columnName_arr[3]['search']['value'], 1, -1);
        if ($filterDepartment) {
            $query = $query
                ->join('positions', 'positions.id', '=', 'users.position_id')
                ->join('departments', 'departments.id', '=', 'positions.department_id')
                ->whereRaw(
                    "concat_ws(' ', departments.name, departments.name_ru) ilike ?",
                    "%{$filterDepartment}%"
                );
        }

        $filterCompany = substr($columnName_arr[4]['search']['value'], 1, -1);
        if ($filterCompany) {
            $query = $query
                ->join('companies', 'companies.id', '=', 'users.company_id')
                ->whereRaw(
                    "concat_ws(' ', companies.name, companies.name_ru) ilike ?",
                    "%{$filterCompany}%"
                );
        }

        return $query;
    }

    public function getDataTableResponseArray($records): array
    {
        $data_arr = array();
        foreach ($records as $record) {
            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->displayName,
                'customProperties' => [
                    "firstname" => $record->firstname,
                    "lastname" => $record->lastname,
                    "firstname_ru" => $record->firstname_ru,
                    "lastname_ru" => $record->lastname_ru,
                ],
                "email" => $record->email,
                "phone" => $record->phone,

                "internal_phone" => $record->internal_phone,
                "rank" => $record->position->displayName ?? '',
                "department" => $record->department?->displayName ?? '',
                "office" => $record->company?->displayName ?? '',

                "documentStatus" => $record->document_status_badge,
                "officeAddress" => $record->officeAddress ?? '-',
                "homeport" => $record->homeport?->short_name . " - " . $record->homeport?->name,
                "planning" => '',
                "avatar" => isset($record->binary_photo) ? "data:image/png;base64," . $record->photo :
                    ($record->sex == 'Female' ?
                        asset('assets/images/avatar/thumbnails/employee_female.png') :
                        asset('assets/images/avatar/thumbnails/employee_male.png')
                    ),
                "use_default_avatar" => ! isset($record->binary_photo),
                "initials" => strtoupper($record->lastname[0] . $record->firstname[0]),
                "slug" => $record->slug,
                "subordinates" => $record->subordinates,
                "date_birth" => $record->date_birth?->format('d.m.Y') ?? '',
            );
        }

        return $data_arr;
    }

    public function store(UserCreateRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (isset($data['photo_file']) === true) {
            $path = $data['photo_file']->getRealPath();
            $logo = file_get_contents($path);
            $base64 = base64_encode($logo);
            $data['binary_photo'] = $base64;
        }

        if (isset($data['is_archive']) === true && $data['is_archive'] === true) {
            $data['employee_type'] = config('enums.employee_type.office_archive');
        }

        $employee = User::create($data);
        $this->updateExtraContacts($data, $employee->id);
        $this->updateAddress($data, $employee);

        if ($data['employee_type'] = config('enums.employee_type.partners')) {
            InfoPartner::create([
                'partner_id' => $employee->id,
                'contractor_id' => $data['contractor_id'] ?? null,
                'country_id' => $data['country_id'] ?? null,
                'position' => $data['position_partner'] ?? null,
            ]);
        }

        return response()->json($employee);
    }

    public function show(User $employee): Response|Application|RedirectResponse|Redirector
    {
        $this->authorize('view', $employee);
        $user = $employee;
        if ($user->employee_type == config('enums.employee_type.seaman_applicants')) {
            return redirect(route('seamen.show', ['seaman' => $user->slug]));
        }

        //bitwise field for select type of documents
        // the sign '&' means select those types where there is a necessary bit,
        // so we get the intersection of types for EMPLOYEES and PARTNERS
        $employee_type = $user->employee_type < 1 ? 'Office Employee' : $user->employee_type;
        $documentCategories = DocumentCategory::with(['documentTypes' => function ($query) use ($user, $employee_type) {
            $query->where('employee_types', '&', config('enums.employee_type_code')[$employee_type]);
        }])
            ->whereHas('documentTypes', function ($query) use ($user, $employee_type) {
                $query->where('employee_types', '&', config('enums.employee_type_code')[$employee_type]);
            })
            ->where('parent_id', null)
            ->orderBy('order')
            ->get();

        $oldestPrevService = $user->previousService?->sortBy('date_from')?->first();
        $russia = Country::where('iso_name', 'RU')->first();
        $countries = Country::where('iso_name', '<>', 'RU')->get();
        $contractors = Contractor::all();
        $contractorTypes = ContractorType::getValues();
        $commerceFields = CommerceField::all();

        $archiveDocuments = [];
        $preExpired = [];
        $expired = [];
        $user->photoNew = $user->photo;
        foreach ($user->documents as $doc) {
            if ($doc->statusCode == 1) {
                $preExpired[] = $doc;
            } elseif ($doc->statusCode == 2) {
                $expired[] = $doc;
            } elseif ($doc->statusCode == -2) {
                $archiveDocuments[] = $doc;
            }
        }

        return response()->view('employees.show', compact(
            'user',
            'documentCategories',
            'archiveDocuments',
            'preExpired',
            'expired',
            'oldestPrevService',
            'russia',
            'countries',
            'contractors',
            'contractorTypes',
            'commerceFields'
        ));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UserUpdateRequest $request, User $employee): Response|Redirector|RedirectResponse|Application|ResponseFactory
    {
        $this->authorize('update', [User::class, $employee]);
        $data = $request->validated();

        if (isset($data['photo_file']) === true) {
            $path = $data['photo_file']->getRealPath();
            $logo = file_get_contents($path);
            $base64 = base64_encode($logo);
            $data['binary_photo'] = $base64;
        }


        $employee->update($data);
        $this->updateExtraContacts($data, $employee->id);
        $this->updateAddress($data, $employee);

        if ($employee->employee_type == config('enums.employee_type.partners')) {
           InfoPartner::updateOrCreate(
                ['partner_id' => $employee->id],
                [
                    'contractor_id' => $data['contractor_id'] ?? null,
                    'country_id' => $data['country_id'] ?? null,
                    'position' => $data['position_partner'],
                ]
            );
           if (isset($data['contractor_id'])) {
               Contractor::where('director_id', $employee->id)
                   ->where('id', '<>', $data['contractor_id'])
                   ->update(['director_id' => null]);
               Contractor::where('responsible_id', $employee->id)
                   ->where('id', '<>', $data['contractor_id'])
                   ->update(['responsible_id' => null]);
           }




        }

        if ($request->ajax() === false) {
            if ($employee->is_seaman === true) {
                return redirect(route('seamen.show', ['seaman' => $employee]));
            }
            return redirect(route('employees.show', ['employee' => $employee]));
        }

        return response('success', 200);
    }

    public function toJson(User $employee): JsonResponse
    {
        return response()->json($employee);
    }

    private function updateExtraContacts(array $request, string $userId)
    {
        foreach (ContactType::asArray() as $key => $type) {
            upsertContacts($request["extra_$key"] ?? [], $userId, $key);
        }
    }

    public function getDocument(Request $request, User $user, string $type)
    {
        try {
            $docType = explode('.', $type)[0] ?? '';
            $docSubType = explode('.', $type)[1] ?? '';
            $docType = DocumentType::fromKey($docType);
            $docSubType = $docType->value::fromKey($docSubType);

            if ($docSubType) {
                $document = $user->documents->where('document_type', $type)->first();
                return response()->json($document ?? null);
            }
        } catch (\Exception $e) {
            return response()->json();
        }
    }

    public function allEmployees(Request $request): JsonResponse
    {
        $employees = User::where('employee_type', config('enums.employee_type.office_employees'))->get();

        return response()->json($employees);
    }

    public function previousService(User $employee): JsonResponse
    {
        $prevService = $employee->previousService->where('date_from', '<>', null);
        $result = [];
        foreach ($prevService as $ps) {
            $position = $ps->position->displayName ?? $ps->position_name;
            $company = $ps->company->displayName ?? $ps->company_name;
            $extra_info = $ps->is_full_time ? '' : '(' . __('Part-time') . ')';
            $result[] = [
                'x' => $ps->date_from->getTimestamp(),
                'x2' => $ps->date_to?->getTimestamp() ?? now()->getTimestamp(),

                'name' => $position,
                'label' => $position,
                'description' => $ps->date_from?->format('d.m.Y') . ' - ' . $ps->date_to?->format('d.m.Y') . " $extra_info",
                'date_from' => $ps->date_from?->format('d.m.Y'),
                'date_to' => $ps->date_to?->format('d.m.Y'),
                'experience' => __('Working experience (years)') . ": " . round($ps->experience_in_days / 365.25, 1),
            ];
        }
        $result[] = [
            'x' => now()->getTimestamp(),

            'name' => '',
            'label' => '',
            'description' => '',
            'date_from' => 0,
            'date_to' => '',
            'experience' => '',
            'color' => 'transparent',
            'marker' => ['enabled' => false]
        ];

        return response()->json($result);
    }

    public function updateAddress(array $request, User $user)
    {
        if ($request['actual_address'] !== null) {
            UserAddress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'address' => $request['actual_address'] ?? '',
                ],
                [
                    'updated_at' => now()
                ]
            );
        }
    }

    public function archiveUser(Request $request, User $user)
    {
        $this->authorize('update', [User::class, $user]);


        $data = [];
        if ($user->employee_type == config('enums.employee_type.office_employees')) {
            $data['employee_type'] = config('enums.employee_type.office_archive');

        } elseif ($user->is_seaman) {
            $data['employee_type'] = config('enums.employee_type.seaman_crew_archive');
        }

        User::find($user->id)->update($data);


        if (!$request->ajax()) {
            if ($user->is_seaman) {
                return redirect(route('seamen.show', ['seaman' => $user]));
            }
            return redirect(route('employees.show', ['employee' => $user]));
        }

        return response('success', 200);
    }

    public function deletePhoto(Request $request, User $user)
    {
        $user->binary_photo = null;
        $user->save();
        return response()->json($user);
    }
}
