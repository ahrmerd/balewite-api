<?php

use App\Models\Course;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Material;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;

uses(RefreshDatabase::class);

beforeEach(
    function () {
        $this->actingAs(User::factory()->create(['authorization_level' => 10]));
        $this->faculty_id = Faculty::factory()->create()->id;
        // $this->asAdminUser = $this->actingAs(User::factory()->create(['authorization_level' => 10]));
        // $this->user = $this->actingAs(User::factory()->create(['authorization_level' => 2]));
    }
);

// beforeEach(fn () => $this->actingAs(User::factory()->create(['authorization_level' => 10])));

it(
    'can add a department',
    function () {
        $this->withoutExceptionHandling();
        // dump($this->asAdminUser);
        $this->post(
            'api/departments',
            [
                'department' => 'TestDepartment',
                'faculty_id' => $this->faculty_id,
            ]
        )
            ->assertStatus(201)->assertJsonStructure(
                [
                    'id', 'department', 'faculty_id', 'created_at', 'updated_at',
                ]
            );
    }
);

it(
    'requires a department name',
    function () {
        // $this->withoutExceptionHandling();
        $this->post(
            '/api/departments',
            [
                'department' => '',
                'faculty_id' => $this->faculty_id,
            ]
        )
            ->assertStatus(422)
            ->assertJson(
                [
                    'message' => 'The department field is required.', 'errors' => [
                        'department' => ['The department field is required.'],
                    ],
                ]
            );
    }
);

it(
    'requires a faculty id',
    function () {
        $this->post(
            '/api/departments',
            [
                'department' => 'test',
                'faculty_id' => '',
            ]
        )
            ->assertStatus(422)
            ->assertJson(
                [
                    'message' => 'The faculty id field is required.', 'errors' => [
                        'faculty_id' => ['The faculty id field is required.'],
                    ],
                ]
            );
    }
);

it(
    'requires that the faculty id is an interger',
    function () {
        $this->post(
            '/api/departments',
            [
                'department' => 'test',
                'faculty_id' => 'str',
            ]
        )
            ->assertStatus(422)
            ->assertJson(
                [
                    'message' => 'The selected faculty id is invalid.', 'errors' => [
                        'faculty_id' => ['The selected faculty id is invalid.'],
                    ],
                ]
            );
    }
);

it(
    'requires that department name has at least 3 characters',
    function () {
        $this->post(
            '/api/departments',
            [
                'department' => 'te',
                'faculty_id' => $this->faculty_id,

            ]
        )->assertStatus(422)->assertJson(
            [
                'message' => 'The department must be at least 3 characters.', 'errors' => [
                    'department' => ['The department must be at least 3 characters.'],
                ],
            ]
        );
    }
);

it(
    'requires a department name to be unique',
    function () {
        $this->post(
            '/api/departments',
            [
                'department' => 'SameNAMe',
                'faculty_id' => $this->faculty_id,
            ]
        );
        $this->post(
            '/api/departments',
            [
                'department' => 'SameNAMe',
                'faculty_id' => $this->faculty_id,
            ]
        )->assertStatus(422)->assertJson(
            [
                'message' => 'The department has already been taken.', 'errors' => [
                    'department' => ['The department has already been taken.'],
                ],
            ]
        );
    }
);

it(
    'can return a list of departments',
    function () {
        Department::factory(3)->create();
        $res = $this->get('api/departments');
        expect($res['data'][0])->toHaveKeys(['id', 'department', 'faculty_id', 'created_at']);
    }
);

it(
    'can return a department by id',
    function () {
        $this->withoutExceptionHandling();
        $department = Department::factory()->create();
        $res = $this->get("api/departments/$department->id")->assertStatus(200);
        expect($res->json()['data'])->toHaveKeys(
            [
                'id', 'department', 'faculty_id', 'created_at',
            ]
        );
    }
);

it('can return courses of departments', function () {
    $this->withoutExceptionHandling();
    $department = Department::factory()
        ->has(Course::factory()->count(3))->create();
    $res = $this->get('api/departments/' . $department->id . '/courses')->assertStatus(200);
    expect($res->json()['data'])->toBeArray()->toHaveLength(3);
    expect($res['data'][0])->toHaveKeys(['id', 'created_at', 'code', 'level_id', 'name']);
});

it('can return quizzes of departments', function () {
    $this->withoutExceptionHandling();
    $department = Department::factory()
        ->has(Course::factory()->has(Quiz::factory()->count(5)))->create();
    $res = $this->get('api/departments/' . $department->id . '/quizzes')->assertStatus(200);
    // dump($res->json());
    expect($res->json()['data'])->toBeArray()->toHaveLength(5);
    expect($res['data'][0])->toHaveKeys(['id', 'created_at', 'course_id', 'year', 'title']);
});

it('can return materials of departments', function () {
    $this->withoutExceptionHandling();
    $department = Department::factory()
        ->has(Course::factory()->has(Material::factory()->count(5)))->create();
    $res = $this->get('api/departments/' . $department->id . '/materials')->assertStatus(200);
    expect($res->json()['data'])->toBeArray()->toHaveLength(5);
    expect($res['data'][0])->toHaveKeys(['id', 'created_at', 'course_id', 'url', 'title']);
});

it('return 404 when department cannot be found by id')->get('api/departments/455')->assertStatus(404);

it(
    'can update a department',
    function () {
        $this->withoutExceptionHandling();
        $department = Department::factory()->create();
        $newname = 'New-Name';
        $banner_url = 'http://newimage';
        $this->put("/api/departments/$department->id", ['department' => $newname, 'banner' => $banner_url])
            ->assertStatus(200)->assertSee('1');
        $newDepartment = Department::findOrFail($department->id);
        expect($newDepartment->department)->toBe($newname);
        expect($newDepartment->banner)->toBe($banner_url);
    }
);

it(
    'can delete a department',
    function () {
        $this->withoutExceptionHandling();
        $department = Department::factory()->create();
        $this->delete('/api/departments/' . $department->id)->assertSee('1');
        $this->assertModelMissing($department);
    }
);

it(
    'requires that user is authorized to create, update and delete department',
    function () {
        $department = Department::factory()->create();
        $this->actingAs(User::factory()->create(['authorization_level' => 2]));
        $this->post('/api/departments')->assertStatus(403);
        $this->put('/api/departments/' . $department->id)->assertStatus(403);
        $this->delete('/api/departments/' . $department->id)->assertStatus(403);
    }
);
