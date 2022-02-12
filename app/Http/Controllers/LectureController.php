<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLectureRequest;
use App\Http\Requests\UpdateLectureRequest;
use App\Models\Lecture;
use Spatie\QueryBuilder\AllowedFilter;

class LectureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filters = [AllowedFilter::exact('course_id'), AllowedFilter::exact('day_id'), AllowedFilter::exact('period_id'), 'location', 'lecturer'];
        $sorts = ['id', 'created_at', 'location', 'day_id', 'period_id'];
        $includes = ['day', 'period', 'period'];
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
