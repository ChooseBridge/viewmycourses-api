<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/18
 * Time: 12:50
 */

namespace App\Service\Abstracts;


interface SchoolCourseCategoryServiceAbstract
{
    public function createCourseCategory($data);

    public function validatorForCreate($data);

    public function schoolHasCourseCategory($schoolId,$courseCategory);
}