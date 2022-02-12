<?php

use App\Models\Announcement;
use App\Models\User;
use App\Models\Faculty;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * *TODO*
 * create a resource [x]
 * validate fields (title, user authentication and announcement body) []
 * get a collection of resource []
 * get a resource []
 * update a resiurce []
 * delete a resource []
 */



it('can create an announcement', function () {
    $this->withoutExceptionHandling();
    asModerator($this);
    $payload =  [
        'title' => 'announcement 1',
        'announcement' => 'i am the body',
        'label' => 'event',
        'priority' => 5,
        'image_url' => 'http://abc.com/a.jpg',
        'image' => true,
    ];
    $payload2 =  [
        'title' => 'announcement 1',
        'announcement' => 'i am the body',
        'label' => 'event',
        'priority' => 5
    ];
    $res1 = $this->post('/api/announcements/', $payload)
        ->assertJson($payload);
    $res2 = $this->post('/api/announcements/', $payload2)
        ->assertJson($payload2);
    expect($res1->json('image'))->toBe(true);
    expect($res2->json('image'))->toBe(false);
});

it('ensures that user is authenticated before creating an announcement', function () {
    $payload =  ['title' => 'announcement 1', 'announcement' => 'i am the body', 'label' => 'event'];
    $this->post('/api/announcements/', $payload)->assertStatus(403);
});

it('ensures that the title and announcement body is required', function () {
    asModerator($this);
    $payload =  [];
    $res = $this->post('/api/announcements/', $payload)->assertStatus(422);
    $res->assertExactJson(
        [
            'message' => 'The title field is required. (and 1 more error)', 'errors' => [
                'title' => ['The title field is required.'],
                'announcement' => ['The announcement field is required.'],
            ],
        ]
    );
});


it('can return a list of announcements', function () {
    Announcement::factory(3)->create();
    $res = $this->get('api/announcements');
    expect($res->json())->toBeArray()->toHaveLength(3);
    expect($res[0])->toHaveKeys(['id', 'title', 'announcement', 'label', 'priority', 'created_at']);
});

it('can return an announcement', function () {
    $announcement = Announcement::factory()->create();
    $res = $this->get("api/announcements/$announcement->id")->assertStatus(200);
    expect($res->json())->toHaveKeys(
        ['id', 'title', 'announcement', 'label', 'priority', 'created_at']
    );
});

it('can update an announcement', function () {
    asModerator($this);
    $announcement = Announcement::factory()->create();
    $newTitle = 'new title';
    $newAnnouncement = 'something';
    $res = $this->put("api/announcements/$announcement->id", ['title' => $newTitle, 'announcement' => $newAnnouncement])->assertStatus(200);
    $updatedArticle = Announcement::query()->findOrFail($announcement->id);
    expect($updatedArticle->title)->toBe($newTitle);
    expect($updatedArticle->announcement)->toBe($newAnnouncement);
});

it('can delete an announcement', function () {
    asModerator($this);
    $announcement = Announcement::factory()->create();
    $this->delete("api/announcements/$announcement->id")->assertStatus(200);
    $this->get("api/announcements/$announcement->id")->assertStatus(404);
});

it(
    'requires that user is authorized to create, update and delete an announcement',
    function () {
        requiresAuthTests($this, 'Announcement', 'api/announcements');
    }
);
