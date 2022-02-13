<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFacultyRequest;
use App\Http\Requests\UpdateFacultyRequest;
use App\Http\Resources\BaseResource;
use App\Models\Faculty;

class FacultyController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Faculty::class, null, [
            'except' => ['index', 'show']
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/faculties",
     *      description="Returns faculties",
     *      operationId="findFaculties",
     *      tags={"faculties"},
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
     *              enum={"id", "-id", "created_at", "-created_at", "faculty", "-faculty"}
     *          )
     *      ),
     *     @OA\Parameter(ref="#components/parameters/range"),
     *     @OA\Response(
     *         response=200,
     *         description="success respones",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Faculty")
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
    public function index()
    {
        $filters = [];
        $sort = ['id', 'created_at, faculty'];
        return requestResponseWithFilterRangeSort(Faculty::class, 'faculty', $filters, $sort, fn ($models) => BaseResource::collection($models));
    }
    public function store(StoreFacultyRequest $request)
    {
        $this->authorize('create', Faculty::class);
        return Faculty::create($request->only('faculty'));
    }

    /**
     * @OA\Get(
     *     path="/api/faculties/{id}",
     *     description="Returns a faculty based on a single ID",
     *     operationId="findFacultyById",
     *     tags={"faculties"},
     *     @OA\Parameter(
     *         description="ID of faculty to fetch",
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
     *         @OA\JsonContent(ref="#/components/schemas/Faculty"),
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
    public function show(Faculty $faculty)
    {
        return new BaseResource($faculty);
    }
    public function update(UpdateFacultyRequest $request, Faculty $faculty)
    {
        $this->authorize('update', Faculty::class);
        $faculty->update($request->only('faculty'));
        return $faculty;
    }
    public function destroy(Faculty $faculty)
    {
        $this->authorize('delete', Faculty::class);
        return $faculty->delete();
    }
}
