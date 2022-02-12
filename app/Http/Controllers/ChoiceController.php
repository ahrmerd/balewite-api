<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChoiceRequest;
use App\Http\Requests\UpdateChoiceRequest;
use App\Http\Resources\ChoiceResource;
use App\Models\Choice;

class ChoiceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Choice::class, null, [
            'except' => ['index', 'show']
        ]);
    }
    public function index()
    {
        return ChoiceResource::collection(Choice::all());
    }
    public function store(StoreChoiceRequest $request)
    {
        return Choice::create($request->only(['question_id', 'choice', 'is_answer']));
    }
    public function show(Choice $choice)
    {
        return new ChoiceResource($choice);
    }
    public function update(UpdateChoiceRequest $request, Choice $choice)
    {
        return $choice->update($request->only(['choice', 'is_answer']));
    }
    public function destroy(Choice $choice)
    {
        return $choice->delete();
    }
}
