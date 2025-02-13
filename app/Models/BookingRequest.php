<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRequest extends Model
{
    use HasFactory;

    // Status
    public const PENDING = 1;
    public const ACCEPTED = 2;
    public const CANCELED = 3;
    public const REJECTED = 4;
    public const COMPLETED = 5;

    public const TIMESLOT_DURATION_MINUTES = 60;
    public const TIMESLOT_DURATION_HOUR = '1:00';

    public const STATUS_UNPAID = 1;
    public const STATUS_PAID = 2;

    public const TAX_AMOUNT = 0.1;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'talent_user_id',
        'price',
        'details',
        'status',
        'transaction_id',
        'requested_delivery_date',
        'completed_at',
        'processed_at',
        'time',
        'duration',
        'report_file_path',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'talent_user_id' => 'integer',
        'price' => 'double',
        'requested_delivery_date' => 'date',
        'completed_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function talentUser()
    {
        return $this->belongsTo(User::class,'talent_user_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class,'transaction_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class,'booking_request_id');
    }

    public function requestAttachments()
    {
        return $this->hasMany(RequestAttachment::class,'booking_request_id');
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, RequestPackage::class,'booking_request_id','package_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, RequestService::class,'booking_request_id','service_id');
    }

    public function requestComments()
    {
        return $this->hasMany(RequestComment::class, 'booking_request_id');
    }

     public function notifications()
    {
        return $this->morphMany(Notification::class, 'notificationable');
    }
    public function scopeIsCompleted($query){
        $query->where('status', self::COMPLETED);
    }

    public function scopeHasNotRequestOnDate($query){
        $query->where('has_request_on_date', false);
    }

    public function scopeHasRequestOnDate($query){
        $query->where('has_request_on_date', true);
    }

    public function requestCharges()
    {
        return $this->hasMany(RequestCharges::class,'booking_request_id');
    }
}
