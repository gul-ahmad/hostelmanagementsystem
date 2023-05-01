<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
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

        $roomId = $this->route('room');

        return [

            'branch_id'                               => 'sometimes|integer',
            'room_number'                             => [
                'sometimes',
                'integer',
                Rule::unique('rooms')->ignore($roomId),
            ],
            'room_floor_number'                       => 'sometimes|integer',
            'room_status'                             => 'sometimes|integer',
            'capacity'                                => 'sometimes|integer',
            'tags'                                    => 'sometimes|array',
            'tags.*'                                  => ['integer', Rule::exists('tags', 'id')],
            'prices'                                  => 'sometimes|array',
            //   'prices.*'                                => 'integer',
            'prices.*.start_date'                     => 'sometimes|date',
            'prices.*.end_date'                       => 'sometimes|date',
            'prices.*.price_for_one_person_booking'   => 'sometimes|integer',
            'prices.*.price_for_two_person_booking'   => 'sometimes|integer',
            'prices.*.price_for_three_person_booking' => 'sometimes|integer',
            'prices.*.discount_on_full_allocation'    =>  'integer', 'min:0', 'max:1000',

        ];
    }
}
