<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\MessageResource;
use App\Http\Requests\StoreMessageRequest;

class MessageController extends Controller
{

    public function index()
    {
        $messages = QueryBuilder::for(Message::class)
            ->allowedFilters('user_id', 'year')
            ->allowedSorts('created_at')
            ->get();
        return MessageResource::collection($messages);
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
