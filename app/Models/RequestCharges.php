<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestCharges extends Model
{
    use HasFactory;

    const PAID_WITH_REQUEST = 1;
    const PAID_WITHOUT_REQUEST = 0;

    public const STATUS_UNPAID = 1;
    public const STATUS_PAID = 2;
    public const STATUS_PAID_WITH_REQUEST = 3;

    public const TAX_AMOUNT = 0.1;
    protected $fillable = [
        'transaction_id',
        'title',
        'description',
        'price',
        'paid_with_request',
    ];


    public function bookingRequest()
    {
        return $this->belongsTo(BookingRequest::class,'booking_request_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class,'transaction_id');
    }
}
