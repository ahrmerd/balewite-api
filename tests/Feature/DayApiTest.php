<?php

use App\Models\Day;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

//create [x]
//validate [x]
//read one [x]
//read all [x]
//delete one [x]
beforeEach(function () {
    $this->endPoint = 'api/days';
});

it('can create a day', function () {
    asAdmin($this);
    $this->post($this->endPoint, ['day' => 'monday'])->assertStatus(201);
    $this->assertDatabaseHas('days', [
        'day' => 'monday'
    ]);
});

it('requires a the day field to create a day', function () {
    asAdmin($this);
    $this->post($this->endPoint, ['day' => ''])->assertStatus(422)->assertJson([
        'message' => 'The day field is required.', 'errors' => [
            'day' => ['The day field is required.'],
        ],
    ]);;
});

it('can return all days', function () {
    $this->withoutExceptionHandling();
    $models = Day::factory(3)->create();
    $res = $this->get($this->endPoint);
    expect($res->json()['data'])->toBeArray()->toHaveLength(3);
    $res->assertJsonStructure(['data' => [0 => ['id', 'created_at', 'day']]]);
});

it('can return a day', function () {
    $model = Day::factory()->create();
    $res = $this->get($this->endPoint . '/' . $model->id);
    $res->assertJsonStructure(['data' => ['id', 'created_at', 'day']]);
    expect($res->json()['data']['day'])->toBe($model->day);
});

it('can update a day', function () {
    $this->withoutExceptionHandling();
    asAdmin($this);
    $model = Day::factory()->create(['day' => 'monday']);
    $newValue = 'saturday';
    $this->put($this->endPoint . '/' . $model->id, ['day' => $newValue])->assertStatus(200);
    $newModel = Day::query()->findOrFail($model->id);
    expect($newModel->day)->toBe($newValue);
});

it('can delete a day', function () {
    $this->withoutExceptionHandling();
    asAdmin($this);
    $model = Day::factory()->create();
    $this->delete($this->endPoint . '/' . $model->id);
    $this->assertModelMissing($model);
});

it('requires authoriztion to create, update, delete', function () {
    requiresAuthTests($this, 'Day', $this->endPoint);
});
