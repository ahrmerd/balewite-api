<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            // 'department' => $this->department->department,
            'course_id' => $this->course_id,
            'title' => $this->title,
            'questions' => QuestionResource::collection($this->questions),
            'year' => $this->year,
            'created_at' => $this->created_at
        ];
    }
}
