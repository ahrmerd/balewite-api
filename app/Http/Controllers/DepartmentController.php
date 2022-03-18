<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Resources\DepartmentResource;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\QuizCollection;
use App\Http\Resources\QuizResource;
use App\Models\Material;
use App\Models\Quiz;
use Spatie\QueryBuilder\QueryBuilder;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Department::class, null, [
            'except' => ['index', 'show']
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/departments",
     *      description="Returns all departments",
     *      operationId="findDepartments",
     *      tags={"departments"},
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
     *              enum={"id", "-id", "created_at", "-created_at", "department", "-department"}
     *          )
     *      ),
     *      @OA\Parameter(
     *          in="query",
     *          name="filter",
     *          description="filters base on the parameter passed e.g filter[faculty_id]=5",
     *          required=false,
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="faculty_id",
     *                  type="array",
     *                  @OA\Items(type="number", example="1")
     *              )
     *          ),
     *      ),
     *     @OA\Parameter(ref="#components/parameters/range"),
     *     @OA\Response(
     *         response=200,
     *         description="success respones",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Department")
     *         ),
     *     ),
     *      @OA\Response(
     *          response=400,
     *          description="Error: Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Requested filter(s) `description, are not allowed. Allowed filter(s) are `created_at` ")
     *         ),
     *     ),
     * )
     */
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


    /**
     * @OA\Get(
     *     path="/api/departments/{id}",
     *     description="Returns a department based on a single ID",
     *     operationId="findDepartmentById",
     *     tags={"departments"},
     *     @OA\Parameter(
     *         description="ID of department to fetch",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="single department response",
     *         @OA\JsonContent(ref="#/components/schemas/Department"),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="not found",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="")
     *          ),
     *     )
     * )
     */
    public function show(Department $department)
    {
        return new DepartmentResource($department);
    }


    /**
     * @OA\Get(
     *     path="/api/departments/{id}/courses",
     *     description="Returns all course registered for a department",
     *     operationId="findDepartmentsCourses",
     *     tags={"departments"},
     *     @OA\Parameter(
     *         description="ID of department for which courses are to be fetch",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="courses respones",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Course")
     *         ),
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="Error: Not Found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Model] 2")
     *         ),
     *     ),
     * )
     */
    public function courses(Department $department)
    {
        $includes = ['departments', 'level', 'materials', 'quizzes', 'lectures'];
        $sorts = ['id', 'code', 'name', 'level_id', 'created_at'];
        $filters = ['code', 'name', AllowedFilter::exact('level_id')];

        // return requestResponseWithInludesFilterSortRange($department->courses(), $includes, $filters, $sorts, fn ($model) => BaseResource::collection($model));
        $query = QueryBuilder::for($department->courses())
            ->allowedFilters($filters)
            ->allowedSorts($sorts)
            ->allowedIncludes($includes)
            ->withRange();
        $courses =  request()->has('page') ? $query
            ->paginate() : $query->get();
        return BaseResource::collection($courses);
    }

    /**
     * @OA\Get(
     *     path="/api/departments/{id}/materials",
     *     description="Returns all materials registered for a department",
     *     operationId="findDepartmentMaterials",
     *     tags={"departments"},
     *     @OA\Parameter(
     *         description="ID of department for which materials are to be fetch",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success respones",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Material")
     *         ),
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="Error: Not Found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Model] 2")
     *         ),
     *     ),
     * )
     */


    public function materials(Department $department)
    {
        $course_ids = [];
        $ids = $department->courses()->get(['courses.id'])->toArray();
        foreach ($ids as $id => $value) {
            $course_ids[] = $value['id'];
        }
        $filters = [AllowedFilter::exact('course_id'), 'title', 'description'];
        $sorts = ['id', 'title', 'created_at', 'course_id'];
        $query = QueryBuilder::for(Material::query()->whereIn('course_id', $course_ids))
            ->allowedFilters($filters)
            ->allowedSorts($sorts)
            ->withRange();
        $materials  = request()->has('page') ? $query
            ->paginate() : $query->get();
        return BaseResource::collection($materials);
    }

    /**
     * @OA\Get(
     *     path="/api/departments/{id}/quizzes",
     *     description="Returns all quizzes associated with a department",
     *     operationId="findDepartmentQuizzes",
     *     tags={"quizzes"},
     *     @OA\Parameter(
     *         description="ID of department for which materials are to be fetch",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success respones",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Quiz")
     *         ),
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="Error: Not Found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Model] 2")
     *         ),
     *     ),
     * )
     */

    public function quizzes(Department $department)
    {
        $course_ids = [];
        $ids = $department->courses()->get(['courses.id'])->toArray();
        foreach ($ids as $id => $value) {
            $course_ids[] = $value['id'];
        }
        $filters = ['title', 'year',  AllowedFilter::exact('course_id')];
        $sorts = ['created_at', 'id', 'year', 'title'];
        $query = QueryBuilder::for(Quiz::query()->whereIn('course_id', $course_ids))
            ->allowedFilters($filters)
            ->allowedSorts($sorts)
            ->withRange();
        $quizzes = request()->has('page') ? $query
            ->paginate() : $query->get();
        return QuizCollection::collection($quizzes);
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
