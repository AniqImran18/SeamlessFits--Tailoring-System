<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tailor extends Model
{
    use HasFactory;

    protected $table = 'tailors'; // Specify the table name if not plural
    protected $primaryKey = 'tailorID'; // Specify the primary key

    protected $fillable = [
        'name',
        'password',
        'phone_number',
        'profile_picture',
    ];

    protected $hidden = [
        'password', // Hide the password attribute
    ];
}
