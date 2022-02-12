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
    public function index(Request $request)
    {
        $filters = [AllowedFilter::exact('priority'), AllowedFilter::exact('user_id'), 'title', 'label'];
        $sorts = ['id', 'created_at', 'title', 'priority'];
        return requestResponseWithFilterRangeSort(Announcement::class, 'announcement', $filters, $sorts, fn ($models) => BaseResource::collection($models));
    }

    public function store(StoreAnnouncementRequest $request)
    {
        $data = $request->only(['title', 'announcement', 'priority', 'label', 'image_url', 'image']);
        if ($request->isNotFilled('image')) $data['image'] = false;
        return auth()->user()->announcements()->create($data);
    }
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
