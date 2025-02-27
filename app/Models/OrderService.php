<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderService extends Model
{
    use HasFactory;

    protected $table = 'order_services';

    protected $fillable = [
        'orderID',
        'serviceID',
        'measurementID',
        'additionalRemark',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'orderID');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'serviceID');
    }

    public function measurement()
    {
        return $this->belongsTo(Measurement::class, 'measurementID');
    }
}
