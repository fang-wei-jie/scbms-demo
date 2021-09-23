<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rates extends Model
{
    use HasFactory;

    protected $fillable = [
        'rateName',
        'rateStatus',
        'ratePrice',
    ];

    public $timestamps = false;
}
