<?php

use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once 'vendor/autoload.php';

$app = new Silex\Application();

include 'config.php';
include 'services.php';

$app->match('/{requestedUrl}', function (Request $symfonyRequest, $requestedUrl) use ($app) {
    $psr7Factory = new DiactorosFactory();
    $request = $psr7Factory->createRequest($symfonyRequest);
    if (!$request->getBody()->isSeekable()) {
        $request = $request->withBody(new GuzzleHttp\Psr7\NoSeekStream($request->getBody()));
    }

    $data = $app['data.router']->data($request);

    return new Response(
        $data,
        200,
        ['Content-Type' => 'application/json']
    );
})->assert('requestedUrl', '.*');

$app->run();