<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    public function services()
    {
        return $this->hasMany(Service::class,'service_category_id');
    }

    // for user Country filter
    public static function serviceCategoriesName(){
        $serviceCategoriesName = self::distinct()->select('id','name')->get()->toArray();
        if ($serviceCategoriesName){
            return (array_combine(array_column($serviceCategoriesName,'id'),array_column($serviceCategoriesName,'name')));
        }else{
            return [];
        }

    }
}
