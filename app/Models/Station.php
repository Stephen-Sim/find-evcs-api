<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    protected $fillable = ['name','address', 'total_charging_stations', 'image', 'latitude', 'longitude', 'admin_id'];
}
