<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfessorComment extends Model
{
    //

    protected $table = 'professor_comment';

    protected $primaryKey = 'comment_id';

    protected $fillable = [
      'comment',
      'create_student_id',
      'professor_id',
    ];
}
