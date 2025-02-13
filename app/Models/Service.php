<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_category_id',
        'name',
        'description',
        'icon',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'service_category_id' => 'integer',
    ];


    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class,'service_category_id');
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, PackageService::class, 'service_id','package_id');
    }

    public function bookingRequest(){
        return $this->belongsToMany(BookingRequest::class, RequestService::class, 'service_id','booking_request_id');
    }

    public function serviceUsers(){
        return $this->belongsToMany(User::class, ServiceUser::class, 'service_id','user_id')->withPivot('price','duration','is_active')->withTimestamps();
    }

    public function serviceUser() {
        return $this->hasOne(ServiceUser::class, 'service_id', 'id');
    }
}
