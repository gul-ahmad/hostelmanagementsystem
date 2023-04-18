<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\StudentGender;

class Student extends Model
{
    use HasFactory;

    protected $casts = [

        'full_name'                => 'string',
        'email'                    => 'string',
        'cnic'                     => 'string',
        'contact_number'           => 'string',
        'emergency_contact_number' => 'string',
        'gender'                   => StudentGender::class,
        'address'                  => 'string',
        'university/college'       => 'string',
        'nationality'              => 'string',
        'passport_number'          => 'string',

    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
