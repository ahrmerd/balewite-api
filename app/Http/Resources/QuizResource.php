<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 * @OA\Xml(name="QuizResource"),
 * @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="course_id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="course", ref="#/components/schemas/Course"),
 * @OA\Property(property="title", type="string"),
 * @OA\Property(property="year", type="string"),
 * @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 * @OA\Property(property="questions", type="array", @OA\Items(ref="#/components/schemas/QuestionResource"))
 * )
 */

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
            'course' => $this->course,
            'title' => $this->title,
            'questions' => QuestionResource::collection($this->questions),
            'year' => $this->year,
            'created_at' => $this->created_at
        ];
    }
}
