<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/16
 * Time: 16:51
 */

namespace App\Service\Abstracts;


interface SchoolDistrictServiceAbstract
{
    public function getDistrictsForPage($limit);

    public function validatorForCreate($data);

    public function createDistrict($data);

    public function getDistrictById($id);

}