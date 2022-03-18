<?php

use App\Models\period;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


beforeEach(function () {
    $this->endPoint = 'api/periods';
});

it('can create a period', function () {
    asAdmin($this);
    $data = ['start_time' => '1pm', 'end_time' => '2pm'];
    $this->post($this->endPoint, $data)->assertStatus(201);
    $this->assertDatabaseHas('periods', $data);
});

it('requires a the period field to create a period', function () {
    asAdmin($this);
    $this->post($this->endPoint, ['' => ''])->assertStatus(422)->assertJson([
        'message' => 'The start time field is required. (and 1 more error)', 'errors' => [
            'start_time' => ['The start time field is required.'],
            'end_time' => ['The end time field is required.'],
        ],
    ]);;
});

it('can return a period', function () {
    $model = period::factory()->create();
    $res = $this->get($this->endPoint . '/' . $model->id);
    // expect($res->json())->toBe($model->toArray());
    expect($res->json()['data']['start_time'])->toBe($model->start_time);
    expect($res->json()['data']['end_time'])->toBe($model->end_time);
});

it('can return all periods', function () {
    $this->withoutExceptionHandling();
    $models = Period::factory(6)->create();
    $res = $this->get($this->endPoint);
    expect($res->json()['data'])->toBeArray()->toHaveLength(6);
    $res->assertJsonStructure(['data' => [0 => ['id', 'created_at', 'start_time']]]);
});

it('can update a period', function () {
    // $this->withoutExceptionHandling();
    asAdmin($this);
    $model = Period::factory()->create();
    $newValue = 'saturperiod';
    $this->put($this->endPoint . '/' . $model->id, ['start_time' => $newValue, 'end_time' => $newValue])->assertStatus(200);
    $newModel = Period::query()->findOrFail($model->id);
    expect($newModel->start_time)->toBe($newValue);
    expect($newModel->end_time)->toBe($newValue);
});

it('can delete a period', function () {
    $this->withoutExceptionHandling();
    asAdmin($this);
    $model = period::factory()->create();
    $this->delete($this->endPoint . '/' . $model->id);
    $this->assertModelMissing($model);
});

it('requires authoriztion to create, update, delete', function () {
    requiresAuthTests($this, 'period', $this->endPoint);
});
