<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/18
 * Time: 12:49
 */

namespace App\Service\Providers;


use Illuminate\Support\ServiceProvider;

class ProfessorCourseServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(
          'App\Service\Abstracts\ProfessorCourseServiceAbstract',
          'App\Service\Concretes\ProfessorCourseServiceConcrete'
        );

    }
}