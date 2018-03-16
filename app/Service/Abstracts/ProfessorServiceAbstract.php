<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/16
 * Time: 20:36
 */

namespace App\Service\Abstracts;


interface ProfessorServiceAbstract
{

    public function getProfessorsForPage($limit);

    public function validatorForCreate($data);

    public function createProfessor($data);
}