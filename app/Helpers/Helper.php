<?php

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

function foo()
{
    return 'foo';
}


function requestResponseWithInludesFilterSortRange($model, $includes, $filters, $sorts, $callback)
{
    $modelInstance = new $model;
    $total = $modelInstance::count();
    $models = QueryBuilder::for($model)
        ->allowedIncludes($includes)
        ->allowedFilters($filters)
        ->allowedSorts($sorts)
        ->withRange()
        ->get();
    $total = $modelInstance->count();
    $modelsCollection =  $callback($models);
    return response($modelsCollection)->header('Total-Count', $total);
}
function requestResponseWithFilterRangeSort($model, $searchField, $filters, $sorts, $callback)
{
    $modelInstance = new $model;
    $total = $modelInstance::count();
    $models = QueryBuilder::for($model)
        ->allowedFilters($filters)
        ->allowedSorts($sorts)
        ->withRange()
        ->searchIn($searchField)
        ->get();
    $modelsCollection =  $callback($models);
    return response($modelsCollection)->header('Total-Count', $total);
}

function asModerator(&$obj)
{
    return $obj->actingAs(User::factory()->create(['authorization_level' => 5]));
}

function asAdmin(&$obj)
{
    return $obj->actingAs(User::factory()->create(['authorization_level' => 8]));
}

function requiresAuthTests(&$testObj, $modelName, $endpoint, $data = [])
{
    $modelPath = "App\Models\\$modelName";
    $modelInstance = new $modelPath;
    $id = $modelInstance::factory()->create()->id;
    $testObj->actingAs(User::factory()->create(['authorization_level' => 2]));
    $testObj->post($endpoint, $data)->assertStatus(403);
    $testObj->put($endpoint . '/' . $id)->assertStatus(403);
    $testObj->delete($endpoint . '/' . $id)->assertStatus(403);
}

function indexWithConditions(Request $request, $model, $withOutResource = false)
{
    $modelPath = "App\Models\\$model";
    $resourceModel = "App\Http\Resources\\$model" . 'Resource';
    $modelInstance = new $modelPath;
    $total = $modelInstance::count();
    // $re
    // response();
    if ($request->query('sort') || $request->query('range') || $request->query('filter')) {
        $range = $request->query('range') ?? [0, 20];
        $offset = $range[0];
        $limit = (int) $range[1] - (int) $range[0];
        $sort = $request->query('sort') ?? ['created_at', 'desc'];
        $filter = (array) $request->query('filter');
        $modelQuery = $modelInstance->orderBy($sort[0], $sort[1])->offset($offset)->limit($limit);
        if ($filter) {
            $modelQuery = $modelQuery->where($filter[0], $filter[1], $filter[2]);
        }
        $models = $modelQuery->get();
    } else {
        $models = $modelInstance->all();
    }
    if ($withOutResource) {
        return response($models)->header('Total-Count', $total);
    }

    return (response($resourceModel::collection($models))->header('Total-Count', $total));
}
