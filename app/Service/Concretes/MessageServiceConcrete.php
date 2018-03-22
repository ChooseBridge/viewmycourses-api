<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/21
 * Time: 13:18
 */

namespace App\Service\Concretes;


use App\Message;
use App\Service\Abstracts\MessageServiceAbstract;

class MessageServiceConcrete implements MessageServiceAbstract
{
    public function createMessage($data)
    {
        $message = Message::create($data);
        return $message;
    }

    public function getMessagesByStudentId($studentId)
    {
        $messages = Message::where('to_student_id',$studentId)->get();
        return $messages;
    }

}