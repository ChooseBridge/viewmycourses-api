<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/16
 * Time: 20:38
 */

namespace App\Service\Providers;


use Illuminate\Support\ServiceProvider;

class ProfessorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
          'App\Service\Abstracts\ProfessorServiceAbstract',
          'App\Service\Concretes\ProfessorServiceConcrete'
        );

    }
}