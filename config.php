<?php

use Sata\FakeServerApi\DataProvider\PathDataProvider;
use Sata\FakeServerApi\DataProvider\ProxyDataProvider;

$app['debug'] = true;

$app['cache.path'] = __DIR__ . '/cache';

$app['data.location'] = __DIR__ . '/data';

$app['data.routers'] = $app->share(function($app){
    // describe your API here
    return [
        // limit and offset parameters only make sense
        '/local/articles[/]' => new PathDataProvider($app['filesystem'], ['limit', 'offset']),
        // all other local data get from local data folder
        '/local/{trail:.*}' => new PathDataProvider($app['filesystem']),
        // all r get from reddit (try request /r/PHP/hot.json)
        // requested every time cause of VoidCache
        '/r/{stub:.*}.json' => new ProxyDataProvider($app['guzzle.reddit'], $app['cache.void']),
        // all r get from reddit (try request /get?your=paramter)
        '/get{stub:.*}' => new ProxyDataProvider($app['guzzle.httpbin'], $app['cache.default']),
        // all r get from reddit (try request /post?your=paramter with POST)
        '/post{stub:.*}' => new ProxyDataProvider($app['guzzle.httpbin'], $app['cache.default'])
    ];
});