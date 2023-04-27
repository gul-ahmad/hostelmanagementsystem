<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class CreateRoomRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [

            'branch_id'         => 'required|integer',
            'room_number'       => 'required|integer|unique:rooms',
            'room_floor_number' => 'required|integer',
            'room_status'       => 'required|integer',
            'capactiy'          => 'required|integer',
            'tags'              => 'array',
            'tags.*'            => ['integer', Rule::exists('tags', 'id')]

        ];
    }
}
