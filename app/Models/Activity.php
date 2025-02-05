<?php

// app/Models/Activity.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id'];

    // Связь с родительским видом деятельности
    public function parentActivity()
    {
        return $this->belongsTo(Activity::class, 'parent_id');
    }

    // Связь с дочерними видами деятельности
    public function childActivities()
    {
        return $this->hasMany(Activity::class, 'parent_id');
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_activity', 'activity_id', 'organization_id');
    }
}

