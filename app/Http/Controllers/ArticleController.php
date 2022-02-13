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

    /**
     * @OA\Get(
     *      path="/api/articles",
     *      description="Returns all articles",
     *      operationId="findArticles",
     *      tags={"articles"},
     *      @OA\Parameter(
     *          in="query",
     *          name="sort",
     *          description="sorts items base of field provided",
     *          @OA\Examples(
     *              example = "-id", summary = "sorts in an descending manner by append a '-' ", value="-id"
     *          ),
     *          @OA\Examples(
     *              example = "id", summary = "sorts in an ascending manner", value="id"
     *          ),
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              enum={"id", "-id", "created_at", "-created_at", "priority", "-priority", "-title", "title"}
     *          )
     *      ),
     *      @OA\Parameter(
     *          in="query",
     *          name="filter",
     *          description="filters base on the parameter passed e.g filter[label]=event",
     *          required=false,
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="user_id",
     *                  type="array",
     *                  @OA\Items(type="number", example="1")
     *              ),
     *              @OA\Property(
     *                  property="priority",
     *                  type="array",
     *                  @OA\Items(type="number", example="1")
     *              ),
     *              @OA\Property(
     *                  property="label",
     *                  type="array",
     *                  @OA\Items(type="string", example="event")
     *              ),
     *              @OA\Property(
     *                  property="title",
     *                  type="array",
     *                  @OA\Items(type="string", example="mss walimah")
     *              ),
     *          ),
     *      ),
     *     @OA\Parameter(ref="#components/parameters/range"),
     *     @OA\Response(
     *         response=200,
     *         description="success respones",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Article")
     *         ),
     *     ),
     *      @OA\Response(
     *          response=400,
     *          description="Error: Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Requested filter(s) `description, are not allowed. Allowed filter(s) are `created_at` ")
     *         ),
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $filters = [AllowedFilter::exact('priority'), AllowedFilter::exact('user_id'), 'title', 'label'];
        $sorts =  ['id', 'created_at', 'title', 'proirity'];
        return requestResponseWithFilterRangeSort(Article::class, 'article', $filters, $sorts, fn ($models) => BaseResource::collection($models));
    }

    public function store(StoreArticleRequest $request)
    {
        return auth()->user()->articles()->create($request->only(['title', 'article', 'label', 'proirity']));
    }

    /**
     * @OA\Get(
     *     path="/api/articles/{id}",
     *     description="Returns an article based on a single ID",
     *     operationId="findArticleById",
     *     tags={"articles"},
     *     @OA\Parameter(
     *         description="ID of article to fetch",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="single article response",
     *         @OA\JsonContent(ref="#/components/schemas/Article"),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="not found",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="")
     *          ),
     *     )
     * )
     */
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
