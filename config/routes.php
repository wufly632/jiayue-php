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

use App\Controller\Appearance\AppearanceController;
use App\Controller\Backend\FileController;
use App\Controller\Cases\CaseController;
use App\Controller\Fave\FaveController;
use App\Controller\Material\MaterialController;
use App\Controller\News\NewsController;
use App\Controller\Product\ProductController;
use App\Controller\Product\StyleController;
use App\Controller\Product\TypesController;
use App\Controller\Serving\ServingController;
use App\Controller\StaticPage\StaticPageController;
use App\Controller\User\UserController;
use App\Middleware\RefreshTokenMiddleware;
use Hyperf\HttpServer\Router\Router;
use App\Controller\Backend\UserController as BackendUserController;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::get('/favicon.ico', function () {
    return '';
});

Router::get('/test', [WechatController::class, 'test']);

Router::addGroup('/api', function (){
    Router::addGroup('/user/',function (){
        Router::post('register', [UserController::class, 'register']);
        Router::post('login',[UserController::class,'login']);
        Router::post('testException',[UserController::class,'testException']);
    });
    Router::addGroup('/user/',function (){
        Router::post('info',[UserController::class,'info']);
    },['middleware' => [RefreshTokenMiddleware::class]]);

    Router::addGroup('/news', function () {
        Router::get('/list', [NewsController::class, 'index']);
        Router::get('/detail/{id:\d+}', [NewsController::class, 'detail']);
    });

    Router::addGroup('/product', function () {
        Router::get('/style', [StyleController::class, 'index']);
        Router::get('/list', [ProductController::class, 'index']);
        Router::get('/detail/{id}', [ProductController::class, 'detail']);

        Router::get('/types', [TypesController::class, 'index']);
        Router::post('/types/save', [TypesController::class, 'save']);
    });

    Router::addGroup('/appearance', function () {
        Router::get('/list', [AppearanceController::class, 'index']);
        Router::get('/detail/{id:\d+}', [AppearanceController::class, 'detail']);
    });

    Router::addGroup('/serving', function (){
        Router::get('/list', [ServingController::class, 'index']);
        Router::get('/detail/{id:\d+}', [ServingController::class, 'detail']);
    });

    Router::addGroup('/material', function (){
        Router::get('/list', [MaterialController::class, 'index']);
    });

    Router::addGroup('/case', function (){
        Router::get('/list', [CaseController::class, 'index']);
        Router::get('/detail/{id}', [CaseController::class, 'detail']);
    });

    Router::addGroup('', function (){
        Router::get('/address/info', [StaticPageController::class, 'addressInfo']);

        Router::get('/about/info', [StaticPageController::class, 'aboutInfo']);

        Router::get('/contact/info', [StaticPageController::class, 'contactInfo']);
    });

    Router::addGroup('/fave', function () {
        Router::get('/list', [FaveController::class, 'list']);
        Router::get('/fave', [FaveController::class, 'fave']);
    },['middleware' => [RefreshTokenMiddleware::class]]);
});


Router::post('/api/backend/login',[BackendUserController::class,'login']);
Router::addGroup('/api/backend', function (){

    Router::post('/picture/upload', [FileController::class, 'pictureUpload']);

    Router::addGroup('/product', function () {
        Router::post('/save', [ProductController::class, 'save']);
        Router::get('/list', [ProductController::class, 'list']);
        Router::post('/delete', [ProductController::class, 'delete']);
        Router::get('/detail/{id}', [ProductController::class, 'detail']);
        Router::get('/style', [StyleController::class, 'index']);
        Router::post('/style/save', [StyleController::class, 'save']);
        Router::post('/style/onoffline', [StyleController::class, 'onOffline']);

        Router::get('/types', [TypesController::class, 'index']);
        Router::post('/types/save', [TypesController::class, 'save']);
        Router::post('/types/onoffline', [TypesController::class, 'onOffline']);
    });

    Router::addGroup('/news', function () {
        Router::get('/list', [NewsController::class, 'index']);
        Router::get('/detail/{id:\d+}', [NewsController::class, 'detail']);
        Router::post('/save', [NewsController::class, 'save']);
    });

    Router::addGroup('/appearance', function () {
        Router::get('/list', [AppearanceController::class, 'index']);
        Router::get('/detail/{id:\d+}', [AppearanceController::class, 'detail']);
        Router::post('/save', [AppearanceController::class, 'save']);
    });

    Router::addGroup('/fave', function () {
        Router::get('/list', [FaveController::class, 'index']);
    });

    Router::addGroup('/case', function (){
        Router::get('/list', [CaseController::class, 'index']);
        Router::get('/detail/{id}', [CaseController::class, 'detail']);
        Router::post('/save', [CaseController::class, 'save']);
        Router::post('/delete', [CaseController::class, 'delete']);
    });

    Router::addGroup('', function (){
        Router::get('/address/info', [StaticPageController::class, 'addressInfo']);
        Router::post('/address/save', [StaticPageController::class, 'addressSave']);

        Router::get('/about/info', [StaticPageController::class, 'aboutInfo']);
        Router::post('/about/save', [StaticPageController::class, 'aboutSave']);

        Router::get('/contact/info', [StaticPageController::class, 'contactInfo']);
        Router::post('/contact/save', [StaticPageController::class, 'contactSave']);
    });

},['middleware' => [RefreshTokenMiddleware::class]]);
