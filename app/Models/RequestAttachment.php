<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestAttachment extends Model
{
    use HasFactory;

    const IMAGE = 1;
    const VIDEO = 2;
    const DOCUMENT = 3;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_request_id',
        'file_type',
        'file_path',
        'description',
        'file_name'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'booking_request_id' => 'integer',
    ];


    public function bookingRequest()
    {
        return $this->belongsTo(BookingRequest::class,'booking_request_id');
    }
}
