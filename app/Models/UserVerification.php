<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
    use HasFactory;

    const STATUS_PENDING = "PENDING";
    const STATUS_VERIFIED = "VERIFIED";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'country_id',
        'phone',
        'token',
        'user_id',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'country_id' => 'integer',
        'user_id' => 'integer',
    ];


    public function country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
