<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;

    protected $table = 'phone_numbers';


    protected $fillable = [
        'organization_id', 'phone_number'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class); // Связь с организацией
    }
}
