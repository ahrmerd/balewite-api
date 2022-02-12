<?php

use App\Models\Article;
use App\Models\User;
use App\Models\Faculty;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * *TODO*
 * create a resource [x]
 * validate fields (title, user authentication and article body) [x]
 * get a collection of resource [x]
 * get a resource [x]
 * update a resiurce [x]
 * delete a resource [x]
 */

it('can create an article', function () {
    $this->withoutExceptionHandling();
    asModerator($this);
    $payload =  ['title' => 'article 1', 'article' => 'i am the body'];
    $res = $this->post('/api/articles/', $payload);
    $res->assertJson($payload);
});

it('ensures that user is authenticated before creating an article', function () {
    $payload =  ['title' => 'title', 'article' => 'i am the body'];
    $res = $this->post('/api/articles/', $payload)->assertStatus(403);
});

it('ensures that the aricle title and the body is required', function () {
    asModerator($this);
    $payload =  ['title' => '', 'article' => ''];
    $res = $this->post('/api/articles/', $payload)->assertStatus(422);
    $res->assertJson(
        [
            'message' => 'The title field is required. (and 1 more error)', 'errors' => [
                'title' => ['The title field is required.'],
                'article' => ['The article field is required.'],
            ],
        ]
    );
});



it('can return a list of articles', function () {
    Article::factory(3)->create();
    $res = $this->get('api/articles');
    expect($res->json())->toBeArray()->toHaveLength(3);
    expect($res[0])->toHaveKeys(['id', 'title', 'article', 'priority', 'created_at']);
});

it('can return an article', function () {
    $article = Article::factory()->create();
    $res = $this->get("api/articles/$article->id")->assertStatus(200);
    expect($res->json())->toHaveKeys(
        ['id', 'title', 'article', 'priority', 'created_at']
    );
});

it('can update an article', function () {
    asModerator($this);
    $article = Article::factory()->create();
    $newTitle = 'new title';
    $newArticle = 'something';
    $res = $this->put("api/articles/$article->id", ['title' => $newTitle, 'article' => $newArticle])->assertStatus(200);
    $updatedArticle = Article::query()->findOrFail($article->id);
    expect($updatedArticle->title)->toBe($newTitle);
    expect($updatedArticle->article)->toBe($newArticle);
});

it('can delete an article', function () {
    asModerator($this);
    $article = Article::factory()->create();
    $res = $this->delete("api/articles/$article->id")->assertStatus(200);
    $res = $this->get("api/articles/$article->id")->assertStatus(404);
});

it(
    'requires that user is authorized to create, update and delete an article',
    function () {
        requiresAuthTests($this, 'Article', 'api/articles');
    }
);
