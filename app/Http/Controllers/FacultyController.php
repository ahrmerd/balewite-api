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
