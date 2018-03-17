<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/17
 * Time: 15:36
 */

namespace App\Service\Providers;


use Illuminate\Support\ServiceProvider;

class SchoolRateServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
          'App\Service\Abstracts\SchoolRateServiceAbstract',
          'App\Service\Concretes\SchoolRateServiceConcrete'
        );

    }
}