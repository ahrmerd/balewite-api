<?php

use App\Models\Day;
use App\Models\User;
use App\Models\Level;
use App\Models\Course;
use App\Models\Period;
use App\Models\Department;
use App\Models\Lecture;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

//create [x]
//validate [x]
//read one [x]
//read all [x]
//delete one [x]
beforeEach(function () {

    $this->endPoint = 'api/lectures';
});

function authorizedData($department)
{
    $course = Course::factory()->hasAttached($department)->create();
    $day_id = Day::factory()->create()->id;
    $period_id = Period::factory()->create()->id;
    return ['course_id' => $course->id, 'day_id' => $day_id, 'period_id' => $period_id, 'location' => 'nlt', 'lecturer' => 'mr A'];
}

it('can create a lecture', function () {
    $department = asAthorizedMod($this)[0];
    // $department = Department::factory()->create();
    $data = authorizedData($department);
    $this->withoutExceptionHandling();
    $this->post($this->endPoint, $data)->assertStatus(201);
    $this->assertDatabaseHas('lectures', $data);
});

it('dosent allow duplicate creation of data', function () {
    $department = asAthorizedMod($this)[0];
    // $department = Department::factory()->create();
    $data = authorizedData($department);
    $this->post($this->endPoint, $data)->assertStatus(201);
    $this->post($this->endPoint, $data)->assertStatus(201);
    $this->post($this->endPoint, $data)->assertStatus(201);
    expect(Lecture::query()->count())->toBe(1);
});

it('requires course_id, day_id, and period_id to create a lecture', function () {
    $this->post($this->endPoint, ['course_id' => ''])->assertStatus(422)->assertJson([
        'message' => 'The course id field is required. (and 3 more errors)', 'errors' => [
            'course_id' => ['The course id field is required.'],
            'day_id' => ['The day id field is required.'],
            'period_id' => ['The period id field is required.'],
            'location' => ['The location field is required.'],
        ],
    ]);
});

it('can return a lecture', function () {
    $model = Lecture::factory()->create();
    $res = $this->get($this->endPoint . '/' . $model->id);
    expect(intval($res->json()['data']['course_id']))->toBe($model->course_id);
    expect(intval($res->json()['data']['day_id']))->toBe($model->day_id);
    expect(intval($res->json()['data']['period_id']))->toBe($model->period_id);
    expect($res->json()['data']['location'])->toBe($model->location);
    expect($res->json()['data']['lecturer'])->toBe($model->lecturer);
});

it('can return a list of lectures', function () {
    $models = Lecture::factory(4)->create();
    $res = $this->get($this->endPoint);
    expect($res->json()['data'])->toBeArray()->toHaveLength(4);
    expect($res['data'][0])->toHaveKeys(['id', 'created_at', 'course_id', 'day_id', 'period_id', 'location', 'lecturer']);
});


it('can update a lecture', function () {
    $department = asAthorizedMod($this)[0];
    // $this->withoutExceptionHandling();
    $data = authorizedData($department);
    $model = Lecture::factory(['course_id' => $data['course_id']])->create();
    $day_id = Day::factory()->create()->id;
    $period_id = Period::factory()->create()->id;
    $lecturer = 'sdsurcourse';
    $this->put($this->endPoint . '/' . $model->id, ['day_id' => $day_id, 'period_id' => $period_id, 'lecturer' => $lecturer])->assertStatus(200);
    $newModel = Lecture::query()->findOrFail($model->id);
    expect(intval($newModel->day_id))->toBe($day_id);
    expect(intval($newModel->period_id))->toBe($period_id);
    expect($newModel->lecturer)->toBe($lecturer);
});

it('requires user from the same department to update a course', function () {
    asModerator($this);
    $data = authorizedData(Department::factory()->create());
    $model = Lecture::factory()->create();
    $this->put($this->endPoint . '/' . $model->id, $data)->assertStatus(403);
});

it('can delete a course', function () {
    $this->withoutExceptionHandling();
    asAdmin($this);
    $model = Lecture::factory()->create();
    $this->delete($this->endPoint . '/' . $model->id);
    $this->assertModelMissing($model);
});

it('requires authoriztion to create, update, delete', function () {
    $data = authorizedData(Department::factory()->create());
    requiresAuthTests($this, 'Lecture', $this->endPoint, $data);
});
