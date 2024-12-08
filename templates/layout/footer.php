<?php

declare(strict_types=1);

$sessionId = session_id();

echo <<<HTML
    
<footer>
Votre session : « {$sessionId} ».
</footer>
HTML;
