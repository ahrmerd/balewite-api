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

    public function index(Request $request)
    {
        $filters = [AllowedFilter::exact('course_id'), 'title'];
        $sorts = ['id', 'title', 'created_at', 'course_id'];
        return requestResponseWithFilterRangeSort(Material::class, 'description', $filters, $sorts, fn ($models) => $models);
    }

    public function store(StoreMaterialRequest $request)
    {
        return Material::create($request->only(['title', 'course_id', 'description', 'url']));
    }

    public function show(Material $material)
    {
        return $material;
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
