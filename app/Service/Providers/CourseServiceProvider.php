<?php


namespace App\Service\Providers;

use Illuminate\Support\ServiceProvider;

class CourseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
          'App\Service\Abstracts\CourseServiceAbstract',
          'App\Service\Concretes\CourseServiceConcrete'
        );

    }
}