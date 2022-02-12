<?php

use App\Models\Course;
use App\Models\User;
use App\Models\Level;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

//create []
//validate []
//read one []
//read all []
//delete one []
function asAthorizedMod(&$obj)
{
    $department = Department::factory()->create();
    $user = User::factory()->for($department)->create(['authorization_level' => 5]);
    $obj->actingAs($user);
    return [$department, $user];
}
beforeEach(function () {
    $this->endPoint = 'api/courses';
});

it('can create a course', function () {

    // asAdmin($this);
    $department = asAthorizedMod($this)[0];
    $level_id = Level::factory()->create()->id;
    $data = ['code' => 'mth101', 'name' => 'algebra', 'level_id' => $level_id, 'department_id' => $department->id];
    $res = $this->post($this->endPoint, $data)->assertStatus(200);
    $id = $res->json()[0]['id'];
    $course = Course::query()->findOrFail($id);
    $this->assertDatabaseHas('course_department',  [
        "course_id" => $course->id,
        "department_id" => $department->id
    ]);
});

it('requires the course code, name and level to create a course', function () {
    asAdmin($this);
    $this->post($this->endPoint, ['code' => ''])->assertStatus(422)->assertJson([
        'message' => 'The department id field is required. (and 3 more errors)', 'errors' => [
            'code' => ['The code field is required.'],
            'name' => ['The name field is required.'],
            'level_id' => ['The level id field is required.'],
            'department_id' => ['The department id field is required.'],
        ],
    ]);;
});

it('can return a course', function () {
    $model = Course::factory()->create();
    $res = $this->get($this->endPoint . '/' . $model->id)->assertStatus(200);
    $res->assertJsonStructure(['id', 'created_at', 'code', 'level_id', 'name']);
    expect($res->json()['code'])->toBe($model->code);
    expect($res->json()['name'])->toBe($model->name);
    expect(intval($res->json()['level_id']))->toBe($model->level_id);
});
it('can return departments of courses', function () {
    $this->withoutExceptionHandling();
    $course = Course::factory()
        ->has(Department::factory()->count(3))->create();
    $res = $this->get($this->endPoint . '/' . $course->id . '/departments')->assertStatus(200);
    expect($res->json())->toBeArray()->toHaveLength(3);
    expect($res[0])->toHaveKeys(['id', 'created_at', 'banner', 'faculty_id', 'department']);
});

it('can return a list of courses', function () {
    $models = Course::factory(4)->create();
    $res = $this->get('api/courses');
    expect($res->json())->toBeArray()->toHaveLength(4);
    expect($res[0])->toHaveKeys(['id', 'created_at', 'code', 'level_id', 'name']);
});

it('can update a course', function () {
    $department = asAthorizedMod($this)[0];
    $this->withoutExceptionHandling();
    $model = Course::factory()->hasAttached($department)->create(['code' => 'moncourse']);
    $newValue = 'saturcourse';
    $newValue2 = 'sdsurcourse';
    $this->put($this->endPoint . '/' . $model->id, ['code' => $newValue, 'name' => $newValue2])->assertStatus(200);
    $newModel = Course::query()->findOrFail($model->id);
    expect($newModel->code)->toBe($newValue);
    expect($newModel->name)->toBe($newValue2);
});

it('requires user from the same department to update a course', function () {
    asModerator($this);
    $model = Course::factory()->create(['code' => 'moncourse']);
    $this->put($this->endPoint . '/' . $model->id)->assertStatus(403);
});

it('can delete a course', function () {
    $this->withoutExceptionHandling();
    asAdmin($this);
    $model = Course::factory()->create();
    $this->delete($this->endPoint . '/' . $model->id);
    $this->assertModelMissing($model);
});

it('requires authoriztion to create, update, delete', function () {
    $department = asAthorizedMod($this)[0];
    $level_id = Level::factory()->create()->id;
    $data = ['code' => 'mth101', 'name' => 'algebra', 'level_id' => $level_id, 'department_id' => $department->id];
    requiresAuthTests($this, 'Course', $this->endPoint, $data);
});
