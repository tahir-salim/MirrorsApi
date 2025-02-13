<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestPackage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_request_id',
        'package_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'booking_request_id' => 'integer',
        'package_id' => 'integer',
    ];


    public function bookingRequest()
    {
        return $this->belongsTo(BookingRequest::class,'booking_request_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class,'package_id');
    }
}
