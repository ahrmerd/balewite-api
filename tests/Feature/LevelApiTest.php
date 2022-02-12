<?php

use App\Models\Level;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

//create [x]
//validate [x]
//read one [x]
//read all [x]
//delete one [x]
beforeEach(function () {
    $this->endPoint = 'api/levels';
});

it('can create a level', function () {
    asAdmin($this);
    $this->post($this->endPoint, ['level' => 100])->assertStatus(201);
    $this->assertDatabaseHas('levels', [
        'level' => 100
    ]);
});

it('requires the level field to create a level', function () {
    asAdmin($this);
    $this->post($this->endPoint, ['level' => ''])->assertStatus(422)->assertJson([
        'message' => 'The level field is required.', 'errors' => [
            'level' => ['The level field is required.'],
        ],
    ]);;
});

it('can return a level', function () {
    $model = Level::factory()->create();
    $res = $this->get($this->endPoint . '/' . $model->id);
    $res->assertJsonStructure(['id', 'created_at', 'level']);
    // expect($res->json())->toBe($model->toArray());
    expect(intval($res->json()['level']))->toBe($model->level);
});

it('can update a level', function () {
    $this->withoutExceptionHandling();
    asAdmin($this);
    $model = Level::factory()->create(['level' => 300]);
    $newValue = 400;
    $this->put($this->endPoint . '/' . $model->id, ['level' => $newValue])->assertStatus(200);
    $newModel = Level::query()->findOrFail($model->id);
    expect(intval($newModel->level))->toBe($newValue);
});

it('can delete a level', function () {
    $this->withoutExceptionHandling();
    asAdmin($this);
    $model = Level::factory()->create();
    $this->delete($this->endPoint . '/' . $model->id);
    $this->assertModelMissing($model);
});

it('requires authoriztion to create, update, delete', function () {
    requiresAuthTests($this, 'Level', $this->endPoint);
});
