<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use App\Middleware\RefreshTokenMiddleware;
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::get('/favicon.ico', function () {
    return '';
});

Router::addGroup('/user/',function (){
    Router::post('login',[\App\Controller\UserController::class,'login']);
    Router::post('testException',[\App\Controller\UserController::class,'testException']);
});
Router::addGroup('/user/',function (){
    Router::post('info',[\App\Controller\UserController::class,'info']);
},['middleware' => [RefreshTokenMiddleware::class]]);
