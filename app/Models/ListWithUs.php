<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListWithUs extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'user_type',
        'name',
        'cellphone_number',
        'email',
        'property_type',
        'city',
        'address',
        'size',
        'property_status',
        'price',
        'bedrooms',
        'bathrooms',
        'garage',
        'description',
        'folder_name',
        'image',
        'created_at',
        'updated_at',
    ];
}
