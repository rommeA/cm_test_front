<?php

namespace App\Http\Controllers\Site;

use App\Models\Seaman;
use App\Models\User;
use function app;
use function config;

trait DatatableAjaxHandler
{
    public abstract function getDataTableResponseArray($records): array;

    public function getDatatableAjaxResponse($request, $employee_type = null): array
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index

        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        if ($columnName == 'name') {
            $columnName = app()->getLocale() == 'en' ? 'lastname' : 'lastname_ru';
        } elseif ($columnName == 'isOnBoard') {
            $columnName = 'is_on_board';
        } elseif ($columnName == 'vessel') {
            $columnName = 'vessel_id';
        } elseif ($columnName == 'company') {
            $columnName = 'company_id';
        }
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        $defaultEmployeeType = $this->getDefaultEmployeeType();
        $archiveEmployeeType = $this->getArchiveEmployeeType();

        $employee_type = $employee_type ?? $defaultEmployeeType;
        if ($request->has('show-archived')) {
            $employee_type = $archiveEmployeeType;
        }

        $query = User::select('users.*')->where('employee_type', '=', $employee_type);


        // to the list of seamen we need to add office employees who are also seaman (is_seaman == true)
        if ($employee_type == config('enums.employee_type.seaman_crew')) {
            $query = Seaman::select('users.*')
                ->where(function ($query) use ($employee_type) {
                    $query->where('users.employee_type', '=', $employee_type)
                        ->orWhere(function ($query) {
                            $query->where('users.employee_type', config('enums.employee_type.office_employees'))
                                ->where('users.is_seaman', true);
                        });
                });
        } elseif ($employee_type == config('enums.employee_type.seaman_applicants')) {
            $query = $query->where('application_form_status', '>', 1);

            $secondQuery = Seaman::select('users.*')
                ->where('employee_type', '=', $employee_type)
                ->whereRelation('applicationChanges', 'new_status', config('enums.application_form_status.checking'));
            if ($searchValue) {
                $secondQuery = $secondQuery
                    ->whereRaw(
                        "concat_ws(' ', lastname, firstname, lastname, " .
                        "lastname_ru,  firstname_ru,  lastname_ru, users.email, users.phone, users.internal_phone) ilike ?",
                        "%{$searchValue}%"
                    );
            }
            $secondQuery = $this->datatableApplyFilters($secondQuery, $columnName_arr);
            $query = $query->union($secondQuery);


        }

        $query = $this->datatableApplyFilters($query, $columnName_arr);

        // Total records
        $totalRecords = $query->count();

        if ($searchValue) {
            $query = $query
                ->whereRaw(
                    "concat_ws(' ', lastname, firstname, lastname, " .
                    "lastname_ru,  firstname_ru,  lastname_ru, users.email, users.phone, users.internal_phone) ilike ?",
                    "%{$searchValue}%"
                );
        }
        $totalRecordswithFilter = $query->count();

        // Get records, also we have included search filter as well
        $records = $query
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)->get();

        $data_arr = $this->getDataTableResponseArray($records);


        return array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
            "DT_RowData"
        );
    }

    public function datatableApplyFilters($query, $columnName_arr)
    {
        return $query;
    }

    public function getDefaultEmployeeType()
    {
        return config('enums.employee_type.office_employees');
    }

    public function getArchiveEmployeeType()
    {
        return config('enums.employee_type.office_archive');
    }
}
