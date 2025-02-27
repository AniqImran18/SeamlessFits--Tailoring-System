<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers'; // Specify the table name if not plural
    protected $primaryKey = 'customerID'; // Specify the primary key

    protected $fillable = [
        'name',
        'password',
        'phone_number',
        'email',
        'profile_picture',
    ];

    protected $hidden = [
        'password', // Hide the password attribute
    ];

    public function measurements()
    {
        return $this->hasMany(Measurement::class, 'customerID');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'customerID');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customerID');
    }
}
