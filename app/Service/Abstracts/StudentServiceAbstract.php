<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/14
 * Time: 20:35
 */
namespace App\Service\Abstracts;

use Illuminate\Http\Request;

interface StudentServiceAbstract{

    public function createStudent($data);

    public function updateStudent($student,$data);

    public function getStudentByUCenterUId($uid);

    public function getStudentByToken($token);

    public function refreshAccessToken($student);

    public function setPoints($delta,$comment,$student);

    public function getPoints($student);

    public function getCurrentStudent();

    public function getStudentById($id);

    public function currentStudentIsVip();



}