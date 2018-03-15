<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/15
 * Time: 14:43
 */

namespace App\Service\Abstracts;


interface ProvinceServiceAbstract
{
    public function getAllProvinces();

    public function getProvincesForPage($limit);

    public function getProvincesByCountryId($countryId);

    public function getProvinceById($id);

    public function validatorForCreate($data);

    public function createProvince($data);
}