<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use App\Http\Resources\BaseResource;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Material::class, null, [
            'except' => ['index', 'show']
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/materials",
     *      description="Returns materials",
     *      operationId="findMaterials",
     *      tags={"materials"},
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
     *                  property="description",
     *                  type="array",
     *                  @OA\Items(type="string", example="pdf")
     *              ),
     *          ),
     *      ),
     *     @OA\Parameter(ref="#components/parameters/range"),
     *     @OA\Response(
     *         response=200,
     *         description="success response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Material")
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
        $filters = [AllowedFilter::exact('course_id'), 'title', 'description'];
        $sorts = ['id', 'title', 'created_at', 'course_id'];
        return requestResponseWithFilterRangeSort(Material::class, 'description', $filters, $sorts, fn ($models) => BaseResource::collection($models));
    }

    public function store(StoreMaterialRequest $request)
    {
        return Material::create($request->only(['title', 'course_id', 'description', 'url']));
    }

    /**
     * @OA\Get(
     *     path="/api/materials/{id}",
     *     description="Returns a material based on a single ID",
     *     operationId="findMaterialById",
     *     tags={"materials"},
     *     @OA\Parameter(
     *          ref="#/components/parameters/id",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="single material response",
     *         @OA\JsonContent(ref="#/components/schemas/Material"),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         ref="#/components/responses/404",
     *     )
     * )
     */
    public function show(Material $material)
    {
        return new BaseResource($material);
    }
    public function update(UpdateMaterialRequest $request, Material $material)
    {
        $material->update($request->only(['title', 'course_id', 'description', 'url']));
        return $material;
    }
    public function destroy(Material $material)
    {
        return $material->delete();
    }
}
