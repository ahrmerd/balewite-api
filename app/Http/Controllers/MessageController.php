<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\MessageResource;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\BaseResource;

class MessageController extends Controller
{

    public function index()
    {
        $filters = ['user_id'];
        $sorts = ['user_id', 'created_at'];
        return requestResponseWithFilterRangeSort(Message::class, 'message', $filters, $sorts, fn ($models) => MessageResource::collection($models));
    }
    public function store(StoreMessageRequest $request)
    {
        return auth()->user()->messages()->create($request->only(['message']));
    }
    public function show(Message $message)
    {
        return new MessageResource($message);
    }

    public function destroy(Message $message)
    {
        return $message->delete();
    }
}
