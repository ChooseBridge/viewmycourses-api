<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/15
 * Time: 21:21
 */

namespace App\Service\Providers;


use Illuminate\Support\ServiceProvider;

class SchoolServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(
          'App\Service\Abstracts\SchoolServiceAbstract',
          'App\Service\Concretes\SchoolServiceConcrete'
        );
    }

}