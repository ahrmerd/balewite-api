<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLevelRequest;
use App\Http\Requests\UpdateLevelRequest;
use App\Http\Resources\BaseResource;
use App\Models\Level;

class LevelController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Level::class, null, [
            'except' => ['index', 'show']
        ]);
    }

    public function index()
    {
        return response(Level::all())->header('Total-Count', Level::query()->count());
    }

    public function store(StoreLevelRequest $request)
    {
        return Level::create($request->only(['level']));
    }

    public function show(Level $level)
    {
        return $level;
    }

    public function update(UpdateLevelRequest $request, Level $level)
    {
        $level->update($request->only(['level']));
    }

    public function destroy(Level $level)
    {
        $level->delete();
    }
}
