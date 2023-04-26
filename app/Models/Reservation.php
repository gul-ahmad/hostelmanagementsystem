<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_CANCELLED = 2;

    protected $casts = [

        'start_date'      => 'immutable_date',
        'end_date'        => 'immutable_date',
        'wifi_password'   => 'string',
        'status' => 'int',
        'room_id'         => 'int',
        'student_id'         => 'int'

    ];

  //  protected $fillable = ['room_id', 'student_id'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
