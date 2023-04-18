<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tags extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public $timestamps = false;

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'rooms_tags');
    }
}
