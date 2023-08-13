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
        'status' => 'int',
        'room_id'         => 'int',
        'student_id'         => 'int',
        'wifi_password'   => 'encrypted'

    ];

    protected $fillable = ['transaction_id', 'room_id', 'user_id', 'start_date', 'end_date', 'price', 'wifi_password'];

    // public function student(): BelongsTo
    // {
    //     return $this->belongsTo(Student::class);
    // }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }


    public function scopeActiveBetween($query, $from, $to)
    {
        $query->whereStatus(Reservation::STATUS_ACTIVE)
            //->betweenDates($from, $to);
            ->whereDate('start_date', '<=', $to)
            ->whereDate('end_date', '>=', $from);
    }
}
