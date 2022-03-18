<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Resources\BaseResource;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Announcement::class, null, [
            'except' => ['index', 'show']
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/announcements",
     *      description="Returns all announcements",
     *      operationId="findAnnouncements",
     *      tags={"announcements"},
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
     *             @OA\Items(ref="#/components/schemas/Announcement")
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
        // dump(BaseResource::class::collection(Announcement::all()));
        // return response(BaseResource::collection(Announcement::all()));
        $filters = [AllowedFilter::exact('priority'), AllowedFilter::exact('user_id'), 'title', 'label'];
        $sorts = ['id', 'created_at', 'title', 'priority'];
        return requestResponseWithFilterRangeSort(Announcement::class, 'announcement', $filters, $sorts, fn ($models) => BaseResource::collection($models), BaseResource::class);
    }

    public function store(StoreAnnouncementRequest $request)
    {
        $data = $request->only(['title', 'announcement', 'priority', 'label', 'image_url', 'image']);
        if ($request->isNotFilled('image')) $data['image'] = false;
        return auth()->user()->announcements()->create($data);
    }

    /**
     * @OA\Get(
     *     path="/api/announcements/{id}",
     *     description="Returns an announcement based on a single ID",
     *     operationId="findAnnouncementById",
     *     tags={"announcements"},
     *     @OA\Parameter(
     *         description="ID of annnouncement to fetch",
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
     *         description="single announcement response",
     *         @OA\JsonContent(ref="#/components/schemas/Announcement"),
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
    public function show(Announcement $announcement)
    {
        return new BaseResource($announcement);
    }
    public function update(UpdateAnnouncementRequest $request, Announcement $announcement)
    {
        $data = $request->only(['title', 'announcement', 'priority', 'label', 'image_url', 'image']);
        if ($request->isNotFilled('image')) $data['image'] = false;
        $announcement->update($data);
        return $announcement;
    }
    public function destroy(Announcement $announcement)
    {
        return $announcement->delete();
    }
}
