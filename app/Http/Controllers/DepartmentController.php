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
     *             @OA\Items(ref="#/components/schemas/Department")
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
