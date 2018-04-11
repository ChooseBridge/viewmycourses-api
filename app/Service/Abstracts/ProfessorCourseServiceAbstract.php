<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/18
 * Time: 12:48
 */

namespace App\Service\Abstracts;


interface ProfessorCourseServiceAbstract
{
    public function createCourse($data);

    public function validatorForCreate($data);

    public function professorHasCourse($professorId,$courseCode);

    public function getCoursesByProfessorId($professorId);

    public function getCourseById($id);

    public function deleteCoursesByProfessorId($professorId);

}