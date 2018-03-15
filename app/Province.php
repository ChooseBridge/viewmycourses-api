<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    //
    protected $table = 'province';

    protected $primaryKey = 'province_id';

    protected $fillable = [
      'province_name',
      'country_id',
    ];

    public function country()
    {
        return $this->belongsTo('App\Country','country_id','country_id');
    }

}
