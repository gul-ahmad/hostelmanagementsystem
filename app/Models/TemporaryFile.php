<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryFile extends Model
{

    use HasFactory;

    protected $table = "temporary_files";
    protected $fillable = ['folder', 'file'];
}
