<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    protected $table = 'message';

    protected $primaryKey = 'messag_id';

    protected $fillable = [
      'message_content',
      'to_student_id',
      'is_read',
    ];

}
