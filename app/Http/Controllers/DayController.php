<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDayRequest;
use App\Http\Requests\UpdateDayRequest;
use App\Http\Resources\BaseResource;
use App\Models\Day;

class DayController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Day::class, null, [
            'except' => ['index', 'show']
        ]);
    }
    public function index()
    {
        return response(Day::all(), 200)->header('Total-Count', Day::query()->count());
    }


    public function store(StoreDayRequest $request)
    {
        return Day::query()->create($request->only(['day']));
    }

    public function show(Day $day)
    {
        return $day;
    }

    public function update(UpdateDayRequest $request, Day $day)
    {
        $day->update($request->only(['day']));
    }

    public function destroy(Day $day)
    {
        return $day->delete();
    }
}
