<?php

// app/Models/Building.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address'];

    // Связь с организациями (одно здание может содержать несколько организаций)
    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }
}

