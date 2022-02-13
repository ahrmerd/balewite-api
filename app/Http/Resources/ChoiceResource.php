<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 * @OA\Xml(name="ChoiceResource"),
 *  @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="choice", type="string"),
 * @OA\Property(property="is_answer", type="boolean"),
 * )
 */
class ChoiceResource extends JsonResource
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
            'choice' => $this->choice,
            'is_answer' => $this->is_answer,
        ];
    }
}
