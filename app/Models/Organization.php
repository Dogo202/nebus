<?php

// app/Models/Organization.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'building_id', 'activity_id'];

    // Связь с таблицей Building (каждая организация принадлежит одному зданию)
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    // Связь с таблицей Activity (каждая организация имеет один вид деятельности)
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'organization_activity', 'organization_id', 'activity_id');
    }

    public function phoneNumbers()
    {
        return $this->hasMany(Phone::class); // Связь с номерами телефонов
    }

}

