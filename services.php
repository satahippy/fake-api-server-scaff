<?php

use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Cache\VoidCache;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Sata\FakeServerApi\DataProvider\RouterDataProvider;

$app['cache.default'] = $app->share(function ($app) {
    return new FilesystemCache($app['cache.path']);
});

$app['cache.void'] = $app->share(function ($app) {
    return new VoidCache();
});

$app['filesystem'] = $app->share(function ($app) {
    return new Filesystem(new Local($app['data.location']));
});

$app['guzzle.reddit'] = $app->share(function ($app) {
    return new GuzzleHttp\Client(['base_uri' => 'https://www.reddit.com']);
});

$app['guzzle.httpbin'] = $app->share(function ($app) {
    return new GuzzleHttp\Client(['base_uri' => 'http://httpbin.org']);
});

$app['data.router'] = $app->share(function ($app) {
    return new RouterDataProvider($app['data.routers']);
});