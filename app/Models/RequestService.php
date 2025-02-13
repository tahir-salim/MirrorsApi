<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestService extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_id',
        'service_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'booking_request_id' => 'integer',
        'service_id' => 'integer',
    ];


    public function bookingRequest()
    {
        return $this->belongsTo(BookingRequest::class,'booking_request_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class,'service_id');
    }
}
