<?php

use App\Http\Resources\DepartmentResource;
use App\Models\Department;
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
    $query = QueryBuilder::for($model)
        ->allowedIncludes($includes)
        ->allowedFilters($filters)
        ->allowedSorts($sorts)
        ->withRange();
    $models = request()->has('page') ? $query->paginate() : $query->get();
    // DepartmentResource::collection($model)->additional()
    return $callback($models)->additional(['Total-Count' => $total]);
}
function requestResponseWithFilterRangeSort($model, $searchField, $filters, $sorts, $callback)
{
    $modelInstance = new $model;
    $total = $modelInstance::count();
    $query = QueryBuilder::for($model)
        ->allowedFilters($filters)
        ->allowedSorts($sorts)
        ->withRange()
        ->searchIn($searchField);
    $models = request()->has('page') ? $query->paginate(intval(request('perPage'))) : $query->get();

    return $callback($models)->additional(['Total-Count' => $total]);
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
