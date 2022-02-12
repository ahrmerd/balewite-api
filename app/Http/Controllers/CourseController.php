<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Course::class, null, [
            'except' => ['index', 'show', 'store']
        ]);
    }
    public function index()
    {
        $includes = ['departments', 'level', 'materials', 'quizzes', 'lectures'];
        $sorts = ['id', 'code', 'name', 'level_id'];
        $filters = ['code', 'name', AllowedFilter::exact('level_id')];
        return requestResponseWithInludesFilterSortRange(Course::class, $includes, $filters, $sorts, fn ($models) => $models);
    }

    public function store(StoreCourseRequest $request)
    {
        $department_id = $request->department_id;
        $this->authorize('create', [Course::class, $department_id]);
        return Course::CreateOrSyncCourseDepartment($department_id, $request->only(['code', 'name', 'level_id']));
    }
    public function show(Course $course)
    {
        return $course;
    }

    public function departments(Course $course)
    {
        return $course->departments;
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        return $course->update($request->only(['code', 'name', 'level_id']));
    }
    public function destroy(Course $course)
    {
        return $course->delete();
    }
}
