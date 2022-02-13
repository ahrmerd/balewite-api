<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 * @OA\Xml(name="QuestionResource"),
 *  @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="question", type="string"),
 * @OA\Property(property="quiz_id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="choices", type="array", @OA\Items(ref="#/components/schemas/ChoiceResource")),
 * )
 */
class QuestionResource extends JsonResource
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
            'question' => $this->question,
            'quiz_id' => $this->quiz_id,
            'choices' => ChoiceResource::collection($this->choices),
        ];
    }
}
