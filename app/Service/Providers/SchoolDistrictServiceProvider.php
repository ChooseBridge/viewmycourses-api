<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/16
 * Time: 16:52
 */

namespace App\Service\Providers;


use Illuminate\Support\ServiceProvider;

class SchoolDistrictServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
          'App\Service\Abstracts\SchoolDistrictServiceAbstract',
          'App\Service\Concretes\SchoolDistrictServiceConcrete'
        );
    }
}