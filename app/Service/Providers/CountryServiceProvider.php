<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/15
 * Time: 13:06
 */
namespace App\Service\Providers;

use Illuminate\Support\ServiceProvider;

class CountryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
          'App\Service\Abstracts\CountryServiceAbstract',
          'App\Service\Concretes\CountryServiceConcrete'
        );

    }
}