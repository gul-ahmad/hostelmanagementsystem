<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Allocation extends Model
{
    use HasFactory;

    const SEPRATE_ROOM = 1;
    const TWO_PERSONS_ROOM = 2;
    const THREE_PERSONS_ROOM = 3;


    protected $casts = [

        'slot1'           => 'bool',
        'slot2'           => 'bool',
        'slot3'           => 'bool',
        'allocation_type' => 'int'
    ];

    public function room(): BelongsTo
    {

        return $this->belongsTo(Room::class);
    }
}
