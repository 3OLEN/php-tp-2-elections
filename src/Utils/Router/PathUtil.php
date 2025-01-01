<?php

declare(strict_types=1);

namespace TpElections\Utils\Router;

class PathUtil
{
    public static function getRequestPath(): string
    {
        return parse_url(url: $_SERVER['REQUEST_URI'], component: PHP_URL_PATH);
    }
}
