<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Settings extends Model
{
    use HasFactory, SoftDeletes;

    public const APP_USER = 'USER';
    public const APP_TALENT = 'TALENT';
    public const MAX_REQUEST_PER_DAY = "MAX_REQUEST_PER_DAY";
    public const TIME_DURATION_LIMIT = 30;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'value',
        'description',
        'show_for_talent',
        'show_for_user',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'show_for_talent' => 'boolean',
        'show_for_user' => 'boolean',
    ];

    public function scopeShowForUser($query){
        $query->where('show_for_user', true);
    }

    public function scopeShowForTalent($query){
        $query->where('show_for_talent', true);
    }
}
