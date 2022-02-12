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

    public function index(Request $request)
    {
        $quizzes = QueryBuilder::for('App\Models\Quiz')
            ->allowedFilters('title', 'year',  AllowedFilter::exact('course_id'),)
            ->allowedSorts(['created_at', 'id', 'year',])
            ->FilterByDepartment()
            ->withRange()
            ->get();
        return response(QuizCollection::collection($quizzes))->header('Total-count', Quiz::query()->count());
    }
    public function store(StoreQuizRequest $request)
    {
        return Quiz::create($request->only(['course_id', 'title', 'year',]));
    }
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
