<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/16
 * Time: 11:34
 */

namespace App\Service\Abstracts;


interface CollegeServiceAbstract
{

    public function getCollegesForPage($limit);

    public function validatorForCreate($data);

    public function createCollege($data);
}