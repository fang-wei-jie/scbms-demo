<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $table = "operation_preferences";

    protected $fillable = [
        'attr',
        'value',
    ];

    public $timestamps = false;
}
