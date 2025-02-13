<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentDetails extends Model
{
    use HasFactory;

    const INACTIVE = 0;
    const ACTIVE = 1;
    const BLOCKED = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'about',
        'avatar',
        'social_instagram',
        'social_snapchat',
        'social_youtube',
        'social_twitter',
        'social_tik_tok',
        'status',
        'is_featured',
        'bank_name',
        'bank_account_owner',
        'bank_iban',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'is_featured' => 'boolean',
    ];


    public function user()
    {
        return $this->belongsTo(\App\Models\User::class,'user_id');
    }

       public function talentPosts()
       {
           return $this->hasMany(\App\Models\TalentPost::class,'user_id');
       }

       public function categories(){
           return $this->belongsToMany(Category::class,CategoryUser::class,'user_id','category_id');
       }

       public function services(){
           return $this->belongsToMany(Service::class,ServiceUser::class,'user_id','service_id')->withPivot('price','duration','is_active');
       }

       public function requestComments(){
           return $this->belongsToMany(BookingRequest::class,RequestComment::class,'user_id','booking_request_id')->withPivot('comment');
       }
       public function requests()
    {
        return $this->hasMany(BookingRequest::class,'talent_user_id', 'user_id');
    }
    public function packages()
    {
        return $this->hasMany(Package::class, 'user_id', 'user_id');
    }

}
