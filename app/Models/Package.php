<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'price',
        'description',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'price' => 'double',
    ];


    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function bookingRequest(){
        return $this->belongsToMany(BookingRequest::class, RequestPackage::class, 'package_id','booking_request_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, PackageService::class,'package_id','service_id')->withTimestamps();
    }
}
