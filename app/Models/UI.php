<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UI extends Model
{
    use HasFactory;

    protected $table = "ui_preferences";

    protected $fillable = [
        'side',
        'navbar_class',
        'navbar_text_class',
        'logo',
    ];

    public $timestamps = false;
}
