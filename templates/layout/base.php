<?php

declare(strict_types=1);

ob_start();
require_once __DIR__ . '/head.php';
$htmlHead = ob_get_clean();

ob_start();
require_once __DIR__ . '/footer.php';
$htmlFooter = ob_get_clean();

if (is_string($pageHeader ?? null) === false) {
    ob_start();
    require_once __DIR__ . '/page_header.php';
    $pageHeader = ob_get_clean();
}

$content ??= '';

echo <<<HTML

<!DOCTYPE html>
<html lang="fr">

{$htmlHead}

<body>
{$pageHeader}

<main>
{$content}
</main>

{$htmlFooter}

</body>
</html>
HTML;
