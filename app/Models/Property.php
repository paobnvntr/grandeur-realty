<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
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
        'status',
        'date_sold',
        'created_at',
        'updated_at',
    ];

    public function analytics()
    {
        return $this->hasOne(ListingAnalytics::class, 'property_id');
    }
}
