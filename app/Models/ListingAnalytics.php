<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListingAnalytics extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'property_id',
        'views',
        'interactions',
        'created_at',
        'updated_at',
    ];

    public function analytics()
    {
        return $this->hasOne(ListingAnalytics::class);
    }
}
