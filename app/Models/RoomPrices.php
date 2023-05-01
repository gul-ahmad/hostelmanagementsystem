<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomPrices extends Model
{

    protected $fillable =
    [
        'room_id',
        'start_date',
        'end_date',
        'price_for_one_person_booking',
        'price_for_two_person_booking',
        'price_for_three_person_booking',
        'discount_on_full_allocation'
    ];

    protected $casts = [

        'room_id'                        => 'int',
        'start_date'                     => 'date',
        'end_date'                       => 'date',
        'price_for_one_person_booking'   => 'int',
        'price_for_two_person_booking'   => 'int',
        'price_for_three_person_booking' => 'int',
        'discount_on_full_allocation'    => 'int'

    ];

    use HasFactory;


    public function room(): BelongsTo

    {

        return $this->belongsTo(Room::class);
    }
}
