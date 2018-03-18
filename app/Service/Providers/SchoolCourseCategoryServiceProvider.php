<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/18
 * Time: 12:51
 */

namespace App\Service\Providers;


use Illuminate\Support\ServiceProvider;

class SchoolCourseCategoryServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(
          'App\Service\Abstracts\SchoolCourseCategoryServiceAbstract',
          'App\Service\Concretes\SchoolCourseCategoryServiceConcrete'
        );

    }
}