<?php

use App\Models\Material;
use App\Models\Course;

use function Pest\Laravel\assertDatabaseHas;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * create material if user is auth
 * validate material fields ['title', 'level' , 'course', 'url']
 * return a list of materials
 * return a material
 * update a material
 * delete a material
 *
 */
it('can create a material', function () {
    asModerator($this);
    $course_id = Course::factory()->create()->id;
    $data = ['title' => 'title', 'course_id' => $course_id, 'description' => 'some', 'url' => 'http://ap.com'];
    $this->post('api/materials', $data)->assertStatus(201)->assertJson($data);
});
it('validates material fields', function () {
    asModerator($this);
    $payload =  [];
    $res = $this->post('api/materials/', $payload)->assertStatus(422);
    $res->assertExactJson(
        [
            'message' => 'The title field is required. (and 3 more errors)', 'errors' => [
                'course_id' => ['The course id field is required.'],
                'title' => ['The title field is required.'],
                'description' => ['The description field is required.'],
                'url' => ['The url field is required.'],
            ],
        ]
    );
});
it('can return a list of material', function () {
    Material::factory(3)->create();
    $res = $this->get('api/materials');
    expect($res->json())->toBeArray()->toHaveLength(3);
    expect($res[0])->toHaveKeys(['id', 'course_id', 'title', 'description', 'url', 'created_at']);
});
it('can return a material', function () {
    $material_id = Material::factory()->create()->id;
    $res = $this->get("api/materials/$material_id")->assertStatus(200);
    expect($res->json())->toHaveKeys(
        ['id', 'course_id', 'title', 'description', 'url', 'created_at']
    );
});
it('can update a material', function () {
    asModerator($this);
    $material = Material::factory()->create();
    $newTitle = 'new title';
    $newDescription = 'something';
    $newUrl = 'http://g.vo';
    $res = $this->put("api/materials/$material->id", ['title' => $newTitle, 'description' => $newDescription, 'url' => $newUrl])->assertStatus(200);
    $updatedMaterial = Material::query()->findOrFail($material->id);
    expect($updatedMaterial->title)->toBe($newTitle);
    expect($updatedMaterial->title)->toBe($newTitle);
    expect($updatedMaterial->description)->toBe($newDescription);
});

it('can delete a material', function () {
    asModerator($this);
    $material = Material::factory()->create();
    $this->delete("api/materials/$material->id")->assertStatus(200);
    $this->get("api/materials/$material->id")->assertStatus(404);
});

it(
    'requires that user is authorized to create, update and delete an announcement',
    function () {
        requiresAuthTests($this, 'Material', 'api/materials');
    }
);
