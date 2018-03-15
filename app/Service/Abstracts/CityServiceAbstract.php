<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/15
 * Time: 15:30
 */

namespace App\Service\Abstracts;


interface CityServiceAbstract
{

    public function getCitysForPage($limit);

    public function validatorForCreate($data);

    public function createCity($data);
}