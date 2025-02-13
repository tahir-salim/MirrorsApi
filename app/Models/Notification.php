<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    // action types
    public const TYPE_BOOKING_ACCEPTED = 1;
    public const TYPE_BOOKING_REJECTED = 2;
    public const TYPE_USER_COMMENT = 3;
    public const TYPE_TALENT_COMMENT = 4;
    public const TYPE_BOOKING_CANCELED = 5;
    public const TYPE_BOOKING_REVIEW = 6;
    public const TYPE_NEW_BOOKING = 7;
    public const TYPE_BOOKING_UPDATE = 8;
    public const TYPE_REVIEW_INQUIRY = 9;
    public const TYPE_REQUEST_PAID = 10;
    public const TYPE_REQUEST_CHARGE_PAID = 10;
    public const TYPE_NEW_REQUEST_CHARGE = 11;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'is_read',
        'action_type',
        'notificationable_id',
        'notificationable_type'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'is_read' => 'boolean',
    ];

     public function notificationable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function scopeUnReadNotification($query){
        $query->where('is_read', false);
    }
}
