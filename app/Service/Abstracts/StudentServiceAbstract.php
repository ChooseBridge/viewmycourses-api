<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/14
 * Time: 20:35
 */
namespace App\Service\Abstracts;

interface StudentServiceAbstract{

    public function createStudent($data);

    public function updateStudent($data);

    public function getStudentByUCenterUId($uid);

}