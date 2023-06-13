<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class RoomImageUpdateRequest extends FormRequest
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
        //Log::info('RoomImageUpdateRequest rules method called');

        return [
            'room.roomImage.files'                    => 'array|required',
            'room.roomImage.files.*.folder'           => 'required',
            'room.roomImage.files.*.file'             => 'required',
            'room.roomImage.roomId'                   => 'required',
        ];
    }
}
