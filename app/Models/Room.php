<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    const  AVAILABLE_FOR_BOOKING     = 1;
    const  NOT_AVAILABLE_FOR_BOOKING = 2;


    protected $fillable = ['branch_id', 'room_number', 'capacity', 'room_floor_number', 'room_status', 'hidden', 'available_slots'];

    protected $casts = [

        'room_number'       => 'int',
        'room_floor_number' => 'int',
        'room_status'       => 'int',
        'hidden'            => 'bool',
        'room_status'   => 'int',
        'capacity'          => 'int'

    ];

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'resource');
    }


    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class);
    // }

    // public function students(): HasMany
    // {
    //     return $this->hasMany(Student::class);
    // }
    public function  users(): HasMany
    {
        return $this->hasMany(User::class);
    }


    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }


    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'featured_image_id');
    }


    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'rooms_tags');
    }
    // public function prices(): HasMany
    // {

    //     return $this->hasMany(RoomPrices::class);
    // }

    public function prices(): HasOne
    {

        return $this->hasOne(RoomPrices::class);
    }
    public function allocations(): HasOne
    {

        return $this->hasOne(Allocation::class);
    }
}
