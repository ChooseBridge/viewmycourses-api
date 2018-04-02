<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolComment extends Model
{
    //
    protected $table = 'school_comment';

    protected $primaryKey = 'comment_id';

    protected $fillable = [
      'comment',
      'create_student_id',
      'school_id',
    ];
}
