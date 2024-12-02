<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

session_start();
$sessionId = session_id();

// Rendu des assets
$path = parse_url(url: $_SERVER['REQUEST_URI'], component: PHP_URL_PATH);
if (str_starts_with(haystack: $path, needle: '/public/')) {
    return false;
}

echo <<<HTML

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>3OLEN - Élections</title>
    <link rel="stylesheet" href="public/styles/app.css">
</head>
<body>
<header>
    <h1>Bienvenue sur le site des élections 3OLEN !</h1>
</header>

<main></main>
    
<footer>
Votre session : « {$sessionId} ».
</footer>
</body>
</html>
HTML;
