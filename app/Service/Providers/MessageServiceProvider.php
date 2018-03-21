<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/21
 * Time: 13:18
 */

namespace App\Service\Providers;


use Illuminate\Support\ServiceProvider;

class MessageServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(
          'App\Service\Abstracts\MessageServiceAbstract',
          'App\Service\Concretes\MessageServiceConcrete'
        );

    }

}