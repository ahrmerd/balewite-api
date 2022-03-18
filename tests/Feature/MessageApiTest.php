<?php

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

//create [x]
//validate message [x]
//read messages [x]
//read message [x]
//delete message [x]
beforeEach(function () {
    $this->endPoint = 'api/messages';
    $this->user = User::factory()->create();
});

it('can create a message', function () {
    $this->withoutExceptionHandling();
    $message = 'the admin is awesome';
    $response = $this->actingAs($this->user)
        ->post($this->endPoint, ['message' => $message])->assertStatus(201);
    $response->assertJsonStructure([
        'id', 'message', 'created_at', 'updated_at',
    ]);
    $this->assertDatabaseHas('messages', [
        'message' => $message,
    ]);
    $response->assertStatus(201);
});

it('requires a message to create a messsage', function () {
    $response = $this->actingAs($this->user)
        ->post($this->endPoint, ['message' => ''])->assertStatus(422)->assertJson([
            'message' => 'The message field is required.', 'errors' => [
                'message' => ['The message field is required.'],
            ],
        ]);;
});

it('can return paginated messages', function () {
    Message::factory(4)->create();
    $res = $this->get($this->endPoint);
    expect($res->json()['data'])->toBeArray()->toHaveLength(4);
    expect($res['data'][0])->toHaveKeys(['id', 'created_at', 'message', 'user']);
});

it('can return a message', function () {
    $this->withoutExceptionHandling();
    $message = Message::factory()->create();
    $res = $this->get($this->endPoint . '/' . $message->id);
    $res->assertJsonStructure(['data' => ['id', 'created_at', 'message', 'user']]);
});

it('can delete a message', function () {
    $this->withoutExceptionHandling();
    $message = Message::factory()->create();
    $this->delete($this->endPoint . '/' . $message->id);
    $this->assertCount(0, Message::all());
});
