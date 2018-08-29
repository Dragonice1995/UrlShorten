<?php

use urlShortenApp\Controller\UserController;
use urlShortenApp\Controller\UrlController;
use urlShortenApp\Repository\UserRepository;
use urlShortenApp\Repository\UrlRepository;
use urlShortenApp\Service\UserService;
use urlShortenApp\Service\UrlService;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\Request;

$app->register(new DoctrineServiceProvider(), [
    'db.options' => [
        'driver' => 'pdo_mysql',
        'host' => '10.10.10.33',
        'dbname' => 'url_shorten',
        'user' => 'root',
        'password' => 'root',
        'charset' => 'utf8'
    ]
]);
$app->register(new ServiceControllerServiceProvider());

$app->before(function(Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

// services

$app['users.controller'] = function ($app) {
    return new UserController($app['users.service'], $app['url.service']);
};

$app['users.service'] = function ($app) {
    return new UserService($app['users.repository']);
};

$app['users.repository'] = function ($app) {
    return new UserRepository($app['db']);
};

$app['url.controller'] = function ($app) {
    return new UrlController($app['users.service'], $app['url.service']);
};

$app['url.service'] = function ($app) {
    return new UrlService($app['url.repository']);
};

$app['url.repository'] = function ($app) {
    return new UrlRepository($app['db']);
};

// routes

$route = $app['controllers_factory'];

$route->post('/users', 'users.controller:register');
$route->get('/users/me', 'users.controller:getUser');
$route->put('/users/me', 'users.controller:updateUser');

$route->post('/users/me/shorten_urls', 'url.controller:createShortUrl');

$route->get('/shorten_urls/{hash}', 'url.controller:redirectToUrl');

$app->mount('/api/v1', $route);