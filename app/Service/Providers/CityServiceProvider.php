<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/15
 * Time: 15:31
 */

namespace App\Service\Providers;


use Illuminate\Support\ServiceProvider;

class CityServiceProvider extends  ServiceProvider
{

    public function register()
    {
        $this->app->bind(
          'App\Service\Abstracts\CityServiceAbstract',
          'App\Service\Concretes\CityServiceConcrete'
        );

    }

}