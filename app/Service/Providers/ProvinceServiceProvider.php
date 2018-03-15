<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/15
 * Time: 14:44
 */

namespace App\Service\Providers;


use Illuminate\Support\ServiceProvider;

class ProvinceServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(
          'App\Service\Abstracts\ProvinceServiceAbstract',
          'App\Service\Concretes\ProvinceServiceConcrete'
        );

    }
}