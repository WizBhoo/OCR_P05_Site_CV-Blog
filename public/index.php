<?php

require '../vendor/autoload.php';

use GuzzleHttp\Psr7\ServerRequest;
use MyWebsite\App;

use function Http\Response\send;

$app = new App();

try {
    $response = $app->run(ServerRequest::fromGlobals());
    send($response);
} catch (Exception $e) {
}
