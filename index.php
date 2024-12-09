<?php

declare(strict_types=1);

use TpElections\Application;
use TpElections\Exception\Web\UnsupportedResourceException;
use TpElections\Web\Router;

require_once __DIR__ . '/vendor/autoload.php';

$router = new Router();

$application = new Application(
    router: $router,
);
try {
    $application->run();
} catch (UnsupportedResourceException) {
    // On laisse le serveur web gérer la ressource
    return false;
}
