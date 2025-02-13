<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceUser extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_id',
        'user_id',
        'price',
        'duration',
        'is_active',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'service_id' => 'integer',
        'user_id' => 'integer',
        'price' => 'double',
        'is_active' => 'integer',
    ];


    public function service()
    {
        return $this->belongsTo(Service::class,'service_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function scopeIsActive($query){
        $query->where('is_active', true);
    }
}
