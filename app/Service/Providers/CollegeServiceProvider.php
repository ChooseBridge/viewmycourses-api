<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/16
 * Time: 11:35
 */

namespace App\Service\Providers;


use Illuminate\Support\ServiceProvider;

class CollegeServiceProvider extends  ServiceProvider
{
    public function register()
    {
        $this->app->bind(
          'App\Service\Abstracts\CollegeServiceAbstract',
          'App\Service\Concretes\CollegeServiceConcrete'
        );

    }
}