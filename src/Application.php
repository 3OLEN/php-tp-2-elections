<?php

declare(strict_types=1);

namespace TpElections;

use TpElections\Web\Router;

readonly class Application
{
    public function __construct(
        private Router $router,
    ) {
    }

    public function run(): void
    {
        session_start();

        $this->router->handleRequest();
    }
}
