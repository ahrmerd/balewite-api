<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
            'department' => $this->department,
            // 'faculty' => $this->faculty->faculty,
            'faculty_id' => $this->faculty_id,
            'created_at' => $this->created_at,
            'banner' => $this->banner,
        ];
    }
}
