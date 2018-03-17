<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/17
 * Time: 15:33
 */

namespace App\Service\Abstracts;


interface ProfessorRateServiceAbstract
{
    public function validatorForCreate($data);

    public function createRate($data);
}