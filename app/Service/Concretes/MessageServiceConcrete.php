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
        foreach ($messages as $message){
            $message->is_read = 1;
            $message->save();
        }
        return $messages;
    }

    public function getUnrReadCountByStudentId($studentId)
    {
        $count = Message::where('to_student_id',$studentId)
            ->where('is_read',0)
            ->count();
        return $count;
    }

}