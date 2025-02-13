<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'country_code',
        'phone_code',
        'is_active',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'is_active' => 'integer',
    ];

    public function getCountryFromPhone($phone)
    {
        if(!$phone) {
            return null;
        }

       $countries = Country::all();

        if ($phone) {
            // remove 00 or + from phone
            if ($phone[0] == '0') {
                $phone = ltrim($phone, '00');
            } elseif ($phone[0] == '+') {
                $phone = ltrim($phone, $phone[0]);
            }

            // get country fom code
             foreach ($countries as $country) {
               $phoneCode = ltrim($country['phone_code'],$country['phone_code'][0]);
                if (strpos($phone, $phoneCode) === 0) {
                    //Starts with the country code
                    return $country;
                }
            }

        }

        return null;
    }

    // for user Country filter
    public static function countryNames(){
        $countryNames = self::distinct()->where('is_active', true)->select('id','name')->get()->toArray();
        if ($countryNames){
            return (array_combine(array_column($countryNames,'id'),array_column($countryNames,'name')));
        }else{
            return [];
        }

    }

    public function usersCountry(){
        return $this->hasMany(User::class, 'country_id');
    }
}
