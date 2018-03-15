<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    protected $table = 'city';

    protected $primaryKey = 'city_id';

    protected $fillable = [
      'city_name',
      'province_id',
      'country_id',
    ];

    public function country()
    {
        return $this->belongsTo('App\Country','country_id','country_id');
    }

    public function province()
    {
        return $this->belongsTo('App\Province','province_id','province_id');
    }

}
