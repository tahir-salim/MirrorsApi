<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestComment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_request_id',
        'user_id',
        'comment',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'booking_request_id' => 'integer',
        'user_id' => 'integer',
    ];


    public function bookingRequest()
    {
        return $this->belongsTo(\App\Models\BookingRequest::class,'booking_request_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class,'user_id');
    }
}
