<?php

require '../vendor/autoload.php';

use MyWebsite\App;

use function Http\Response\send;

$app = new App();

try {
    $response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
    send($response);
} catch (Exception $e) {
}
