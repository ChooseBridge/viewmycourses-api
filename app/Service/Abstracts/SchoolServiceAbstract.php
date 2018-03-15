<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/15
 * Time: 21:18
 */

namespace App\Service\Abstracts;


interface SchoolServiceAbstract
{
    public function createSchool($data);

    public function validatorForCreate($data);

}