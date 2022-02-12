<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Question::class, null, [
            'except' => ['index', 'show']
        ]);
    }

    public function index()
    {
        $models = Question::all();
        $total = $models->count();
        $modelsCollection =  QuestionResource::collection($models);
        return response($modelsCollection)->header('Total-Count', $total);
    }
    public function store(StoreQuestionRequest $request)
    {
        $question = Question::create($request->only(['quiz_id', 'question']));
        foreach ($request->input('incorrect') as $choice) {
            $question->choices()->create(['choice' => $choice]);
        };
        $question->choices()->create(['choice' => $request->input('answer'), 'is_answer' => true]);

        return new QuestionResource($question);
    }
    public function show(Question $question)
    {
        return new QuestionResource($question);
    }
    public function update(UpdateQuestionRequest $request, Question $question)
    {
        $question->update($request->only(['question']));
        return $question;
    }
    public function destroy(Question $question)
    {
        return $question->delete();
    }
}
