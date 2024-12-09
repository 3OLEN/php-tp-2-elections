<?php

declare(strict_types=1);

namespace TpElections\Exception\Web;

/**
 * Ressource ou fichier non trouvé.
 */
class NotFoundException extends \Exception
{
    public function __construct(
        public readonly string $path,
    ) {
        parent::__construct(message: 'Resource not found!', code: 404);
    }
}
