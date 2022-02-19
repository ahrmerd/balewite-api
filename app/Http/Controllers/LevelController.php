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


    /**
     * @OA\Get(
     *      path="/api/levels",
     *      description="Returns levels",
     *      operationId="findLevels",
     *      tags={"levels"},
     *     @OA\Response(
     *         response=200,
     *         description="success respones",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Level")
     *         ),
     *     ),
     *      @OA\Response(
     *          response=400,
     *          description="Error: Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Requested filter(s) `description, are not allowed. Allowed filter(s) are `created_at` ")
     *         ),
     *     ),
     * )
     */

    public function index()
    {
        return response(Level::all())->header('Total-Count', Level::query()->count());
    }

    public function store(StoreLevelRequest $request)
    {
        return Level::create($request->only(['level']));
    }

    /**
     * @OA\Get(
     *     path="/api/levels/{id}",
     *     description="Returns a level based on a single ID",
     *     operationId="findLevelById",
     *     tags={"levels"},
     *     @OA\Parameter(
     *         description="Id of level to fetch",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success response",
     *         @OA\JsonContent(ref="#/components/schemas/Level"),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="not found",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="")
     *          ),
     *     )
     * )
     */
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
