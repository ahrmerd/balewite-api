<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Resources\QuizResource;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\QuizCollection;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Article;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Quiz::class, null, [
            'except' => ['index', 'show',]
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/quizzes",
     *      description="Returns quizzes",
     *      operationId="findQuizzes",
     *      tags={"quizzes"},
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
     *              enum={"id", "-id", "created_at", "-created_at", "course_id", "-course_id", "-title", "title"}
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
     *                  property="title",
     *                  type="array",
     *                  @OA\Items(type="string", example="mat101")
     *              ),
     *              @OA\Property(
     *                  property="year",
     *                  type="array",
     *                  @OA\Items(type="string", example="2012")
     *              ),
     *          ),
     *      ),
     *     @OA\Parameter(ref="#components/parameters/range"),
     *     @OA\Response(
     *         response=200,
     *         description="success respones",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Quiz")
     *         ),
     *     ),
     *      @OA\Response(
     *         response="400",
     *         ref="#/components/responses/400"
     *      )
     * )
     */
    public function index(Request $request)
    {
        $quizzes = QueryBuilder::for('App\Models\Quiz')
            ->allowedFilters('title', 'year',  AllowedFilter::exact('course_id'),)
            ->allowedSorts(['created_at', 'id', 'year', 'title'])
            ->allowedIncludes(['course'])
            ->withRange()
            ->get();
        return response(QuizCollection::collection($quizzes))->header('Total-count', Quiz::query()->count());
    }
    public function store(StoreQuizRequest $request)
    {
        return Quiz::create($request->only(['course_id', 'title', 'year',]));
    }

    /**
     * @OA\Get(
     *     path="/api/quizzes/{id}",
     *     description="Returns a quiz based on a single ID",
     *     operationId="findQuizById",
     *     tags={"quizzes"},
     *     @OA\Parameter(
     *          ref="#/components/parameters/id",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="single quiz response",
     *         @OA\JsonContent(ref="#/components/schemas/QuizResource"),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         ref="#/components/responses/404",
     *     )
     * )
     */
    public function show(Quiz $quiz)
    {
        return new QuizResource($quiz);
    }
    public function update(UpdateQuizRequest $request, Quiz $quiz)
    {
        $quiz->update($request->only(['title', 'year',]));
        return $quiz;
    }
    public function destroy(Quiz $quiz)
    {
        return $quiz->delete();
    }
}
