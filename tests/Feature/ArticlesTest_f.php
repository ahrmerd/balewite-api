<?php

namespace Tests\Feature;

use App\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticlesTest extends TestCase
{
    use RefreshDatabase;

    protected $data = ['category_id' => 1, 'title'=>'some post', 'article' => 'some post ok now its up to teb'];

    /** @test */
    public function an_article_can_be_created()
    {
        $this->withoutExceptionHandling();
        $this->post('api/articles', $this->data)->assertCreated();
        $this->assertDatabaseHas('articles', $this->data);
    }

    /** @test */
    public function an_article_can_be_returned()
    {
        $this->post('api/articles', $this->data)->assertCreated();
        $this->get('api/articles/1')->assertOk()
            ->assertJson($this->data);
    }

    /** @test */
    public function a_list_of_articles_can_be_returned()
    {
        $this->post('api/articles', $this->data)->assertCreated();
        $this->get('api/articles')->assertOk()
            ->assertJsonStructure([['id', 'category_id', 'title', 'article', 'created_at', 'updated_at']]);
    }

    /** @test */
    public function an_article_requires_a_name()
    {
        $this->post('api/articles', ['category_id' => 1, 'article' => '', 'title' => 'some title'])->assertStatus(422);
    }

    /** @test */
    public function an_article_requires_a_title()
    {
        $this->post('api/articles', ['category_id' => 1, 'article' => 'i have to have at least 10 charas', 'title' => ''])->assertStatus(422);
    }

    /** @test */
    public function the_name_of_the_article_is_more_than_2_charactser()
    {
        $this->post('api/articles', ['category_id' => 1,  'article' => 'Ad'])->assertStatus(422);
    }

    /** @test */
    public function an_article_requires_a_category_id()
    {
        $this->post('api/articles', ['article' => 'my full post'])->assertStatus(422);
    }

    /** @test */
    public function an_article_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $this->post('api/articles', $this->data)->assertCreated();
        $this->put('api/articles/1', ['article' => 'Super-some post must be more than'])->assertOk();
        $this->assertEquals('Super-some post must be more than', Article::first()->article);
    }


    /** @test */
    public function an_article_can_be_deleted()
    {
        $this->withoutExceptionHandling();
        $this->post('api/articles', $this->data)->assertCreated();
        $this->delete('api/articles/1')->assertOk();
        $this->assertCount(0, Article::all());
    }
}
