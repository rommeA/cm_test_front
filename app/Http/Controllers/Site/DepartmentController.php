<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    protected function departmentPositions(Request $request, Department $department)
    {
        $positions = $department->positions;
        return response()->json($positions);
    }
}
