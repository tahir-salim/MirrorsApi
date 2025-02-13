<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;


    const INITIATED = 'INITIATED';
    const CANCELLED = 'CANCELLED';
    const DECLINED = 'DECLINED';
    const CAPTURED = 'CAPTURED';
    const FAILED = 'FAILED';
    const UNKNOWN = 'UNKNOWN';

    const PENDING = 1;
    const CANCEL = 2;
    const COMPLETED = 3;

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'amount' => 'double',
        'is_success' => 'boolean',
        'paid_at' => 'datetime',
        'usd_amount' => 'double',
        'tap_response' => 'json',
    ];

    protected $hidden = [
        'tap_customer_id',
        'tap_charge_id',
        'tap_response',
    ];

    public static function GET_TAP_STATUS()
    {
        return [
            static::INITIATED => ucwords(strtolower(str_replace('_', ' ', static::INITIATED))),
            static::CANCELLED => ucwords(strtolower(str_replace('_', ' ', static::CANCELLED))),
            static::DECLINED => ucwords(strtolower(str_replace('_', ' ', static::DECLINED))),
            static::CAPTURED => ucwords(strtolower(str_replace('_', ' ', static::CAPTURED))),
            static::FAILED => ucwords(strtolower(str_replace('_', ' ', static::FAILED))),
            static::UNKNOWN => ucwords(strtolower(str_replace('_', ' ', static::UNKNOWN))),
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'status',
        'amount',
        'tap_customer_id',
        'tap_charge_id',
        'tap_status',
        'tap_response',
        'currency',
        'payment_link',
        'is_success',
        'paid_at',
        'usd_amount',
        'origin',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bookingRequest()
    {
        return $this->hasOne(BookingRequest::class, 'transaction_id');
    }

    public function requestCharge()
    {
        return $this->hasOne(RequestCharges::class, 'transaction_id');
    }
}
