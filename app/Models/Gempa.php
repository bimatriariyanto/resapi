<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gempa extends Model
{
    protected $table = 'gempas';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['date', 'coordinates', 'latitude', 'longitude', 'magnitude', 'depth', 'area', 'potential', 'subject', 'headline', 'description', 'felt', 'instruction', 'shakemap'];
}
