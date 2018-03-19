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

    public function getRatesForPage($limit);

    public function getRateById($id);

    public function getRatesByProfessorId($professorId);

    public function approveRateById($id);

    public function rejectRateById($id);


}