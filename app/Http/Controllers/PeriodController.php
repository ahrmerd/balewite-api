<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeriodRequest;
use App\Http\Requests\UpdatePeriodRequest;
use App\Http\Resources\BaseResource;
use App\Models\Day;
use App\Models\Period;

class PeriodController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Period::class, null, [
            'except' => ['index', 'show']
        ]);
    }

    public function index()
    {
        $sorts = ['id', 'created_at', 'start_time', 'end_time'];
        return requestResponseWithFilterRangeSort(Period::class, '', [], $sorts, fn ($m) => BaseResource::collection($m));
        // return response(Period::all(), 200)->header('Total-Count', Period::query()->count());
    }

    public function store(StorePeriodRequest $request)
    {
        return Period::query()->create($request->only(['start_time', 'end_time']));
    }

    public function show(Period $period)
    {
        return new BaseResource($period);
    }

    public function update(UpdatePeriodRequest $request, Period $period)
    {
        $period->update($request->only(['start_time', 'end_time']));
        return $period;
    }


    public function destroy(Period $period)
    {
        return $period->delete();
    }
}
