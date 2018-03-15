<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    //

    protected $table = 'country';

    protected $primaryKey = 'country_id';

    protected $fillable = [
      'country_name',
    ];

    public function provinces()
    {
        return $this->hasMany('App\Province','country_id','country_id');
    }
}
