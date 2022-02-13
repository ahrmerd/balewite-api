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

    /**
     * @OA\Get(
     *      path="/api/courses",
     *      description="Returns courses",
     *      operationId="findCourses",
     *      tags={"courses"},
     *      @OA\Parameter(
     *          in="query",
     *          name="sort",
     *          description="sorts items base of field provided",
     *          @OA\Examples(
     *              example = "-id", summary = "sorts in an descending manner by append a '-' ", value="-id"
     *          ),
     *          @OA\Examples(
     *              example = "id", summary = "sorts in an ascending manner", value="id"
     *          ),
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              enum={"id", "-id", "created_at", "-created_at", "code", "-code", "-name", "name"}
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          style="deepObject",
     *          description="filters base on the parameter passed e.g filter[field]=value",
     *          required=false,
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="level_id",
     *                  type="array",
     *                  @OA\Items(type="number", example="1")
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="array",
     *                  @OA\Items(type="string", example="mat101")
     *              ),
     *              @OA\Property(
     *                  property="name",
     *                  type="array",
     *                  @OA\Items(type="string", example="Elementary Algebra")
     *              ),
     *          ),
     *      ),
     *     @OA\Parameter(
     *          name="include",
     *          in="query",
     *          required=false,
     *          description="includes the details of the field included e.g include='materials'",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(type="string", enum={"department", "level", "materials", "quizzes", "lectures"})
     *          )
     *      ),
     *     @OA\Parameter(ref="#components/parameters/range"),
     *     @OA\Response(
     *         response=200,
     *         description="success response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Course")
     *         ),
     *     ),
     *      @OA\Response(
     *         response="400",
     *         ref="#/components/responses/400"
     *      )
     * )
     */
    public function index()
    {
        $includes = ['departments', 'level', 'materials', 'quizzes', 'lectures'];
        $sorts = ['id', 'code', 'name', 'level_id', 'created_at'];
        $filters = ['code', 'name', AllowedFilter::exact('level_id')];
        return requestResponseWithInludesFilterSortRange(Course::class, $includes, $filters, $sorts, fn ($models) => $models);
    }

    public function store(StoreCourseRequest $request)
    {
        $department_id = $request->department_id;
        $this->authorize('create', [Course::class, $department_id]);
        return Course::CreateOrSyncCourseDepartment($department_id, $request->only(['code', 'name', 'level_id']));
    }

    /**
     * @OA\Get(
     *     path="/api/courses/{id}",
     *     description="Returns a material based on a single ID",
     *     operationId="findCoursesById",
     *     tags={"courses"},
     *     @OA\Parameter(
     *          ref="#/components/parameters/id",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success response",
     *         @OA\JsonContent(ref="#/components/schemas/Course"),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         ref="#/components/responses/404",
     *     )
     * )
     */
    public function show(Course $course)
    {
        return $course;
    }

    /**
     * @OA\Get(
     *     path="/api/courses/{id}/departments",
     *     description="Returns a material based on a single ID",
     *     operationId="findCourseDepartmentsById",
     *     tags={"materials"},
     *     @OA\Parameter(
     *          ref="#/components/parameters/id",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success response",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Department")),
     *
     *     ),
     *     @OA\Response(
     *         response="404",
     *         ref="#/components/responses/404",
     *     )
     * )
     */
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
