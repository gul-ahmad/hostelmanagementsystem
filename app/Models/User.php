<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use App\Enums\StudentGender;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'full_name',
        'email',
        'cnic',
        'contact_number',
        'emergency_contact_number',
        'gender',
        'address',
        'university/college',
        'nationality',
        'passport_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at'        => 'datetime',
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



    public function roles(): BelongsToMany
    {

        return $this->belongsToMany(Role::class, 'role_users');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }


    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
