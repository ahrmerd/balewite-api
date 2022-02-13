<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLectureRequest;
use App\Http\Requests\UpdateLectureRequest;
use App\Models\Lecture;
use Spatie\QueryBuilder\AllowedFilter;

class LectureController extends Controller
{

    /**
     * @OA\Get(
     *      path="/api/lectures",
     *      description="Returns lectures",
     *      operationId="findLectures",
     *      tags={"lectures"},
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
     *              enum={"id", "-id", "created_at", "-created_at", "course_id", "-course_id", "-location", "location", "day_id", "-day_id", "period_id", "-period_id"}
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
     *                  property="course_id",
     *                  type="array",
     *                  @OA\Items(type="number", example="1")
     *              ),
     *              @OA\Property(
     *                  property="period_id",
     *                  type="array",
     *                  @OA\Items(type="number", example="1")
     *              ),
     *              @OA\Property(
     *                  property="day_id",
     *                  type="array",
     *                  @OA\Items(type="number", example="1")
     *              ),
     *              @OA\Property(
     *                  property="location",
     *                  type="array",
     *                  @OA\Items(type="string", example="nlt")
     *              ),
     *              @OA\Property(
     *                  property="lecturer",
     *                  type="array",
     *                  @OA\Items(type="string", example="lecturer a")
     *              ),
     *          ),
     *      ),
     *     @OA\Parameter(
     *          name="include",
     *          in="query",
     *          required=false,
     *          description="includes the details of the field included e.g include='course'",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(type="string", enum={"day", "period", "course"})
     *          )
     *      ),
     *     @OA\Parameter(ref="#components/parameters/range"),
     *     @OA\Response(
     *         response=200,
     *         description="success respones",
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
        $filters = [AllowedFilter::exact('course_id'), AllowedFilter::exact('day_id'), AllowedFilter::exact('period_id'), 'location', 'lecturer'];
        $sorts = ['id', 'created_at', 'location', 'day_id', 'period_id', 'course_id'];
        $includes = ['day', 'period', 'course'];
        return requestResponseWithInludesFilterSortRange(Lecture::class, $includes, $filters, $sorts, fn ($models) => $models);
    }

    public function store(StoreLectureRequest $request)
    {
        // $this->authorize('create', Lecture::class);
        $this->authorize('create', [Lecture::class, $request->course_id]);
        $data = $request->only(['course_id', 'day_id', 'period_id', 'location', 'lecturer']);
        $res = Lecture::query()->firstOrCreate($data);
        return response($res, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/lecture/{id}",
     *     description="Returns a material based on a single ID",
     *     operationId="findLectureById",
     *     tags={"lectures"},
     *     @OA\Parameter(
     *          ref="#/components/parameters/id",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success response",
     *         @OA\JsonContent(ref="#/components/schemas/Lecture"),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         ref="#/components/responses/404",
     *     )
     * )
     */

    public function show(Lecture $lecture)
    {
        return $lecture;
    }

    public function update(UpdateLectureRequest $request, Lecture $lecture)
    {
        $this->authorize('update', $lecture);
        $data = $request->only(['course_id', 'day_id', 'period_id', 'location', 'lecturer']);
        return $lecture->update($data);
    }

    public function destroy(Lecture $lecture)
    {
        $this->authorize('delete', $lecture);
        return $lecture->delete();
    }
}
