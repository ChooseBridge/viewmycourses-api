<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/15
 * Time: 13:03
 */

namespace App\Service\Abstracts;


interface CountryServiceAbstract
{

    public function getAllCountrys();

    public function getCountrysForPage($limit);

    public function validatorForCreate($data);

    public function createCountry($data);

}