<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/6/6
 * Time: 9:26
 */

namespace App\Service\Abstracts;


interface CourseServiceAbstract
{

    public function getCourseForPage($limit);

    public function validatorForCreate($data);

    public function createCourse($data);

    public function getCourseById($id);
}