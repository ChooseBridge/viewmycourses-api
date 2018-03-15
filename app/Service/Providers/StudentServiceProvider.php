<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/14
 * Time: 20:25
 */

namespace App\Service\Providers;

use Illuminate\Support\ServiceProvider;

class StudentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
          'App\Service\Abstracts\StudentServiceAbstract',
          'App\Service\Concretes\StudentServiceConcrete'
        );

//        $this->app->singleton('StudentService', function () {
//            return $this->app->make('App\Service\Abstracts\StudentServiceAbstract');
//        });
    }
}