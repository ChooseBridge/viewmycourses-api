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

    public function getRatesForPage($limit,$queryCallBack);

    public function getRateById($id);

    public function getRatesByProfessorId($professorId);

    public function getEffortBySchoolId($schoolId);

    public function getEffortByProfessorId($professorId);

    public function getCheckedRatesBySchoolId($schoolId);

    public function getCheckedRatesByProfessorId($professorId);

    public function getRatesByStudentId($studentId, $orderBy);

    public function approveRateById($id);

    public function rejectRateById($id);

    public function thumbsUpRateById($id,$student);

    public function thumbsDownRateById($id,$student);

    public function deleteRatesByProfessorId($professorId);


}