<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Features extends Model
{
    use HasFactory;

    protected $table = "features_preferences";

    protected $fillable = [
        'name',
        'value',
    ];

    public $timestamps = false;
}
