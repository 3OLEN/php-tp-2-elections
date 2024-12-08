<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

session_start();

// Rendu des assets
$path = parse_url(url: $_SERVER['REQUEST_URI'], component: PHP_URL_PATH);
if (str_starts_with(haystack: $path, needle: '/public/')) {
    return false;
}

// View avec paramètres utilisables
$pageTitle = 'Bienvenue sur le site des élections 3OLEN !';
require_once __DIR__ . '/templates/layout/base.php';
