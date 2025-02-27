<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $primaryKey = 'appointmentID';

    protected $fillable = [
        'customerID',
        'serviceID',
        'date',
        'time',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerID');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'serviceID');
    }
}
