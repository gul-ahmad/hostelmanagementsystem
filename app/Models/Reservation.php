<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    const APPROVAL_APPROVED  = 1;
    const APPROVAL_PENDING   = 2;
    const APPROVAL_REJECTED  = 3;

    protected $casts = [

        'status'          => 'int',
        'start_date'      => 'immutable_date',
        'end_date'        => 'immutable_date',
        'wifi_password'   => 'string',
        'approval_status' => 'int'

    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
