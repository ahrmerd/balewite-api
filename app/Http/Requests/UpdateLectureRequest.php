<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLectureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'course_id' => 'exists:courses,id',
            'day_id' => 'exists:days,id',
            'period_id' => 'exists:periods,id',
            'location' => 'string',
            'lecturer' => 'string',
        ];
    }
}
