<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services'; // Specify the table name if necessary
    protected $primaryKey = 'serviceID'; // Set the primary key
    public $timestamps = false; // Disable timestamps if not needed

    protected $fillable = [
        'category', 'name', 'price',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'serviceID');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_services', 'serviceID', 'orderID');
    }

}
