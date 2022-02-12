<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Resources\DepartmentResource;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Department::class, null, [
            'except' => ['index', 'show']
        ]);
    }
    public function index(Request $request)
    {
        $sorts = ['id', 'created_at', 'department'];
        $filters = [AllowedFilter::exact('faculty_id')];
        return requestResponseWithFilterRangeSort(Department::class, 'department', $filters, $sorts, fn ($models) => DepartmentResource::collection($models));
    }
    public function store(StoreDepartmentRequest $request)
    {
        return Department::create($request->only(['faculty_id', 'department', 'banner']));
    }
    public function show(Department $department)
    {
        return new DepartmentResource($department);
    }

    public function courses(Department $department)
    {
        return $department->courses;
    }
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $department->update($request->only('department', 'banner'));
        return $department;
    }
    public function destroy(Department $department)
    {
        return $department->delete();
    }
}
