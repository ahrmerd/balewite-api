<?php

use App\Models\Faculty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can add a faculty if user is authorized', function () {
    $this->actingAs(User::factory()
        ->create(['authorization_level' => 2]))
        ->post('/api/faculties', ['faculty' => 'anotherFaculty'])
        ->assertStatus(403);
    $this->actingAs(User::factory()
        ->create(['authorization_level' => 10]))
        ->post('/api/faculties', ['faculty' => 'TestFaculty'])
        ->assertStatus(201)
        ->assertJsonStructure(
            ['id', 'faculty', 'created_at', 'updated_at',]
        );
    $this->assertDatabaseHas(
        'faculties',
        ['faculty' => 'TestFaculty',]
    );
});

it('requires a faculty name to create a faculty', function () {
    $this->actingAs(User::factory()->create(['authorization_level' => 10]))
        ->post('/api/faculties', ['faculty' => ''])
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The faculty field is required.', 'errors' => [
                'faculty' => ['The faculty field is required.'],
            ],
        ]);
});

it('requires a faculty name to be unique', function () {
    $this->actingAs(User::factory()->create(['authorization_level' => 10]))
        ->post('/api/faculties', ['faculty' => 'SameNAMe']);
    $this->post('/api/faculties', ['faculty' => 'SameNAMe'])
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The faculty has already been taken.', 'errors' => [
                'faculty' => ['The faculty has already been taken.'],
            ],
        ]);
});

it('can return a list of faculties', function () {
    Faculty::factory(3)->create();
    $res = $this->get('api/faculties');
    expect($res->json()['data'])->toBeArray()->toHaveLength(3);
    expect($res['data'][0])->toHaveKeys(['id', 'faculty', 'created_at']);
});

it('can return a faculty', function () {
    $this->withoutExceptionHandling();
    $faculty = Faculty::factory()->create();
    $res = $this->get("api/faculties/$faculty->id")->assertStatus(200);
    expect($res->json()['data'])->toHaveKeys([
        'id', 'faculty', 'created_at',
    ]);
});

it('will return 404 if no faculty is found')->get('api/faculties/1')->assertStatus(404);

it('can update a faculty name', function () {
    $this->withoutExceptionHandling();
    $faculty = Faculty::factory()->create();
    $newname = 'New-Name';
    $this->actingAs(User::factory()->create(['authorization_level' => 10]))
        ->put("/api/faculties/$faculty->id", ['faculty' => $newname])
        ->assertStatus(200)->assertSee('1');
    expect(Faculty::findOrFail($faculty->id)->faculty)->toBe($newname);
});

it('can delete a faculty', function () {
    $this->withoutExceptionHandling();
    $faculty = Faculty::factory()->create(['faculty' => 'yes']);
    $this->actingAs(User::factory()->create(['authorization_level' => 10]))
        ->delete('/api/faculties/' . 1)->assertSee('1');
    $this->assertModelMissing($faculty);
});
