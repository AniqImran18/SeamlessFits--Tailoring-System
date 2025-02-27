<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders'; // Specify the table name if not plural
    protected $primaryKey = 'orderID'; // Specify the primary key

    protected $fillable = [
        'date', 'time', 'remark', 'status', 'serviceID', 'measurementID', 'customerID', 'tailorID'
    ];

    public function services()
    {
        return $this->hasManyThrough(
            Service::class,
            OrderService::class,
            'orderID',    // Foreign key on OrderService table
            'serviceID',  // Foreign key on Service table
            'orderID',    // Local key on Orders table
            'serviceID'   // Local key on OrderService table
        );
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerID');
    }

    public function tailor()
    {
        return $this->belongsTo(Tailor::class, 'tailorID');
    }

    public function orderServices()
    {
        return $this->hasMany(OrderService::class, 'orderID');
    }


}

