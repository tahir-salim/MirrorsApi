<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory,SoftDeletes,HasApiTokens,Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'country_id',
        'phone',
        'phone_verified_at',
        'password',
        'role_id',
        'avatar',
        'is_blocked',
        'device_os',
        'device_os_version',
        'device_token',
        'device_name',
        'app_version',
        'last_ip_address',
        'last_activity',
        'is_social',
        'provider_id',
        'google_id',
        'provider',
        'provider_token',
        'branch_origin',
        'branch_origin_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'is_blocked' => 'boolean',
        'last_activity' => 'datetime',
        'is_social' => 'boolean',
    ];

    // ROLES
    const ROLE_ADMIN = 1;
    const ROLE_TALENT = 2;
    const ROLE_USER = 3;

    const USER_CACHE = 'user-id-';
    const TALENT_CACHE = 'talent-id-';
    const USER_TRENDING_CACHE = 'trending-user-id';
    const TALENT_CATEGORIES_CACHE = 'trending-categories-id';
    const TALENT_TRENDING_CACHE = 'trending-talent-id';

    public function isAdminRole(){
        return $this->role_id === self::ROLE_ADMIN;
    }

    public function isTalentRole(){
        return $this->role_id === self::ROLE_TALENT;
    }

    public function country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class,'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class,'user_id');
    }

    public function requests()
    {
        return $this->hasMany(BookingRequest::class,'user_id');
    }

    public function talentDetail(){
        return $this->hasOne(TalentDetails::class,'user_id');
    }

    public function talentSchedules(){
        return $this->hasMany(TalentSchedule::class,'user_id');
    }

    public function talentRequests(){
        return $this->hasMany(BookingRequest::class,'talent_user_id');
    }

    public function packages()
    {
        return $this->hasMany(Package::class,'user_id');
    }

    public function talentPosts()
    {
        return $this->hasMany(TalentPost::class,'user_id');
    }

    public function categories(){
        return $this->belongsToMany(Category::class,CategoryUser::class,'user_id','category_id');
    }

    public function services(){
        return $this->belongsToMany(Service::class,ServiceUser::class,'user_id','service_id')->withPivot('price','duration','is_active');
    }

    public function requestComments(){
        return $this->belongsToMany(BookingRequest::class,RequestComment::class,'user_id','booking_request_id')->withPivot('comment')->withTimestamps();
    }

    public function verification(){
        return $this->hasOne(UserVerification::class, 'user_id');
    }

    public function scopeIsTalent($query){
        $query->where('role_id', self::ROLE_TALENT);
    }

    public function scopeIsUser($query){
        $query->where('role_id', self::ROLE_USER);
    }

    public function scopeIsVerified($query){
        $query->whereNotNull('phone_verified_at');
    }

    public function scopeIsNotBlocked($query){
        $query->where('is_blocked', false);
    }

    // for booking request talent filter only
    public static function talentNames(){
        $countryNames = self::distinct()->isTalent()->where('is_blocked', false)->select('id','name')->get()->toArray();
        if ($countryNames){
            return (array_combine(array_column($countryNames,'id'),array_column($countryNames,'name')));
        }else{
            return [];
        }

    }

    public function messages(){
        return $this->hasMany(Message::class,'user_id');
    }

    public function chats(){
        return $this->belongsToMany(Chat::class,ChatUser::class,'user_id','chat_id');
    }
}
