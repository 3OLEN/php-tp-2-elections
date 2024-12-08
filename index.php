<?php

declare(strict_types=1);

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once __DIR__ . '/vendor/autoload.php';

session_start();

// Rendu des assets
$path = parse_url(url: $_SERVER['REQUEST_URI'], component: PHP_URL_PATH);
if (str_starts_with(haystack: $path, needle: '/public/')) {
    return false;
}

// View avec Twig
$twigTemplateLoader = new FilesystemLoader(paths: __DIR__ . '/templates');
$twigEngine = new Environment(
    loader: $twigTemplateLoader,
    options: [
        'strict_variables' => true,
    ],
);
$twigEngine->addGlobal(name: 'session_id', value: session_id());

echo $twigEngine->render(
    name: 'pages/index.html.twig',
    context: [
        'page_title' => 'Bienvenue sur le site des élections 3OLEN !',
        'group' => '3OLEN-DEV',
    ]
);
