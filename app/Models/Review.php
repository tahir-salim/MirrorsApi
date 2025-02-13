<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_request_id',
        'rating',
        'details',
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

    public const STATUS_PENDING = 'PENDING';
    public const STATUS_APPROVED = 'APPROVED';
    public const STATUS_REJECTED = 'REJECTED';

    public static function GET_STATUSES()
    {
        return [
            static::STATUS_PENDING => ucwords(strtolower(str_replace('_', ' ', static::STATUS_PENDING))),
            static::STATUS_APPROVED => ucwords(strtolower(str_replace('_', ' ', static::STATUS_APPROVED))),
            static::STATUS_REJECTED => ucwords(strtolower(str_replace('_', ' ', static::STATUS_REJECTED))),
        ];
    }

    public function bookingRequest()
    {
        return $this->belongsTo(BookingRequest::class,'booking_request_id');
    }
}
