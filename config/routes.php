<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;

Router::addRoute(['POST'], '/upload', 'App\Controller\IndexController@upload', ['middleware' => [\App\Middleware\FileCheckMiddleware::class]]);
Router::get('/s/{code}', 'App\Controller\IndexController@show');
Router::get('/browse', 'App\Controller\IndexController@browse');
Router::get('/test', 'App\Controller\IndexController@test');