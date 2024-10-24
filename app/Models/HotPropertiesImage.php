<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotPropertiesImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'hot_properties_name',
        'image',
    ];
}
