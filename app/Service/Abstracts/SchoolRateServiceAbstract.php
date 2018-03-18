<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/17
 * Time: 15:35
 */

namespace App\Service\Abstracts;


interface SchoolRateServiceAbstract
{
    public function validatorForCreate($data);

    public function createRate($data);

    public function getRatesForPage($limit);

    public function getRateById($id);

    public function approveRateById($id);

    public function rejectRateById($id);


}