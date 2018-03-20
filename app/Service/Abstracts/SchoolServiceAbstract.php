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

    public function approveSchoolById($id);

    public function rejectSchoolById($id);

    public function getSchoolsForPage($limit,$queryCallBack);

    public function getAllCheckedSchoolsForPage($limit,$queryCallBack);

    public function getSchoolById($id);

    public function getAllSchools($queryCallBack);

    public function getAllCheckedSchools($queryCallBack);

    public function getAllCheckedSchoolsGroupCountry();

    public function isCheckedById($id);


}