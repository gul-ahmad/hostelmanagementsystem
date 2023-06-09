<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'path',
    ];

    //Gul here
    //used polymorhpic ,morph many relation
    //images can belong to rooms or reviews or any other resouce/model
    public function resource(): MorphTo
    {

        return $this->morphTo();
    }
}
