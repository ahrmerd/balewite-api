<?php
//create a quiz [x]
//add questions to a quiz and choices to questions [x]
//return a list of questions [x]
//return a quiz with questions and choices [x]
//update a quiz [x]
//update a question [x]
//update choices choise [x]
//update choices to be an answer [x]
//delete a quiz [x]
//delete a question [x]
//delete choices [x]

use App\Http\Resources\QuizResource;
use App\Models\Choice;
use App\Models\Course;
use App\Models\Department;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->quizEndPoint = 'api/quizzes';
    $this->questionEndPoint = 'api/questions';
    $this->choiceEndPoint = 'api/choices';
});

it('can create a quiz', function () {
    asModerator($this);
    $this->withoutExceptionHandling();
    $course = Course::factory()->create();
    $data = ['title' => 'MAT101', 'course_id' => $course->id, 'year' => '2014'];
    $response = $this
        ->post($this->quizEndPoint, $data)->assertStatus(201)->assertJsonStructure([
            'id', 'title', 'year', 'created_at', 'updated_at',
        ]);
    $this->assertDatabaseHas('quizzes', $data);
});

function createQuizQuestionsWithChoices($m)
{
    $quiz = Quiz::factory()->create();
    $question = 'who are you';
    $answer = 'someone';
    $incorrect = ['me', 'you'];
    $response = $m
        ->post($m->questionEndPoint, [
            'question' => $question,
            'quiz_id' => $quiz->id,
            'answer' => $answer,
            'incorrect' => $incorrect,
        ]);
    return ['response' => $response, 'question' => $question, 'answer' => $answer, 'quiz' => $quiz];
};
it('can add questions to a quiz', function () {
    $this->withoutExceptionHandling();
    asModerator($this);
    $res = createQuizQuestionsWithChoices($this);
    $response = $res['response']->assertStatus(201);
    $response->assertJsonStructure(['data' => ['id', 'question', 'choices']]);
    $this->assertDatabaseHas('questions', [
        'question' => $res['question'],
    ]);
    $this->assertDatabaseHas('choices', [
        'choice' => $res['answer'],
        'is_answer' => true,
    ]);

    $response->assertStatus(201);
});

it('can return a list of quizzes', function () {
    $quiz = Quiz::factory(4)->create();
    $res = $this->get($this->quizEndPoint);
    expect($res->json()['data'])->toBeArray()->toHaveLength(4);
    // dump($res->json());
    expect($res['data'][0])->toHaveKeys(['id', 'course_id', 'title', 'year']);
});

it('can return a single quiz with questions and choices', function () {
    $this->withoutExceptionHandling();
    $quiz = Quiz::factory()
        ->has(Question::factory()
            ->count(4)
            ->has(Choice::factory()
                ->count(3)))
        ->create();
    $res = $this->get($this->quizEndPoint . '/' . $quiz->id)->assertStatus(200);
    $res->assertJsonStructure(['data' => ['id', 'course_id', 'title', 'questions' => [['id', 'question', 'choices']]]]);
});

it('will return 404 if quiz is found', function () {
    $res = $this->get($this->quizEndPoint . '/1')->assertStatus(404);
    // dump($res);
});

it('can update the title of a quiz', function () {
    asModerator($this);
    $quiz = Quiz::factory()->create();
    $newTitle = 'newsest title';
    $this->put($this->quizEndPoint . '/' . $quiz->id, ['title' => $newTitle])->assertStatus(200);
    expect(Quiz::findOrFail($quiz->id)->title)->toBe($newTitle);
});
it('can update a question', function () {
    asModerator($this);
    $question = Question::factory()->create();
    $newQuestion = 'i am an updated question';
    $this->put($this->questionEndPoint . '/' . $question->id, ['question' => $newQuestion])->assertStatus(200)->assertSee('1');
    expect(Question::findOrFail($question->id)->question)->toBe($newQuestion);
});
it('can update a choice', function () {
    asModerator($this);
    $choice = Choice::factory()->create();
    $newChoice = 'i am an updated choice';
    $this->put($this->choiceEndPoint . '/' . $choice->id, ['choice' => $newChoice])->assertStatus(200)->assertSee('1');
    expect(Choice::findOrFail($choice->id)->choice)->toBe($newChoice);
});

it('can make a choice to be an answer', function () {
    asModerator($this);
    $choice = Choice::factory(['is_answer' => 0])->create();
    $this->put($this->choiceEndPoint . '/' . $choice->id, ['is_answer' => 1])->assertStatus(200)->assertSee('1');
    expect(Choice::findOrFail($choice->id)->is_answer)->toBe(true);
    $this->put($this->choiceEndPoint . '/' . $choice->id, ['is_answer' => 0])->assertStatus(200)->assertSee('1');
    expect(Choice::findOrFail($choice->id)->is_answer)->toBe(false);
});

it('can delete a quiz', function () {
    asModerator($this);
    $this->withoutExceptionHandling();
    $quiz = Quiz::factory()->create();
    $this->delete($this->quizEndPoint . '/' . $quiz->id)->assertSee('1');
    $this->assertCount(0, Quiz::all());
});

it('can delete a question', function () {
    $this->withoutExceptionHandling();
    asModerator($this);
    $question = Question::factory()->create();
    $this->delete($this->questionEndPoint . '/' . $question->id)->assertSee('1');
    $this->assertCount(0, Question::all());
});

it('can delete a choice', function () {
    asModerator($this);
    $this->withoutExceptionHandling();
    $choice = Choice::factory()->create();
    $this->delete($this->choiceEndPoint . '/' . $choice->id)->assertSee('1');
    $this->assertCount(0, Choice::all());
});
