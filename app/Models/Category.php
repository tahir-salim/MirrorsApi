<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'is_active',
        'is_featured',
        'image_wide',
        'image_square',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];


    public function subCategories()
    {
        return $this->hasMany(SubCategory::class,'category_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class,CategoryUser::class,'category_id','user_id');
    }

    public function scopeIsActive($query){
        $query->where('is_active', true);
    }

    public function scopeIsFeatured($query){
        $query->where('is_featured', true);
    }
    public function scopeIsNew($query){
        $query->orderBy('created_at', 'DESC');
    }
}
