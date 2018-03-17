<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/17
 * Time: 15:34
 */

namespace App\Service\Providers;


use Illuminate\Support\ServiceProvider;

class ProfessorRateServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
          'App\Service\Abstracts\ProfessorRateServiceAbstract',
          'App\Service\Concretes\ProfessorRateServiceConcrete'
        );

    }

}