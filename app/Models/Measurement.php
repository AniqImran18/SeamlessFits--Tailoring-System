<?php

// app/Models/Measurement.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    protected $primaryKey = 'measurementID';

    protected $fillable = [
        'customerID',
        'length',
        'waist',
        'shoulder',
        'hip',
        'wrist',
        'remark',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, OrderService::class, 'measurementID', 'orderID');
    }

}

