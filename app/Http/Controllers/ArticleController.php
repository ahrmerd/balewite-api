<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\BaseResource;
use Illuminate\Http\Client\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Article::class, null, [
            'except' => ['index', 'show']
        ]);
    }
    public function index(Request $request)
    {
        return requestResponseWithFilterRangeSort(Article::class, 'article', [AllowedFilter::exact('priority'), AllowedFilter::exact('user_id'),], ['id', 'created_at', 'title', 'proirity'], fn ($models) => BaseResource::collection($models));
    }

    public function store(StoreArticleRequest $request)
    {
        return auth()->user()->articles()->create($request->only(['title', 'article', 'label', 'proirity']));
    }

    public function show(Article $article)
    {
        return new BaseResource($article);
    }
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $article->update($request->only('title', 'article', 'proirity', 'label'));
        return $article;
    }
    public function destroy(Article $article)
    {
        return $article->delete();
    }
}
