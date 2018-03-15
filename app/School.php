<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    //

    protected $table = 'school';

    protected $primaryKey = 'school_id';

    protected $fillable = [
      'school_name',
      'school_nick_name',
      'country_id',
      'province_id',
      'city_id',
      'website_url',
      'your_email',
      'check_status',
    ];

    const PENDING_CHECK = 0;
    const APPROVE_CHECK = 1;
    const REJECT_CHECK = 2;
}
