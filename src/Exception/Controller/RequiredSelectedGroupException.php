<?php

declare(strict_types=1);

namespace TpElections\Exception\Controller;

use TpElections\Utils\Router\PathUtil;

class RequiredSelectedGroupException extends \Exception
{
    public static function createForResourceAccess(): static
    {
        $path = PathUtil::getRequestPath();

        return new static(
            message: "Un groupe doit être sélectionné pour accéder à la page \"{$path}\"."
        );
    }

    public static function createForAction(string $action): static
    {
        return new static(
            message: "Un groupe doit être sélectionné pour effectuer l'action \"{$action}\"."
        );
    }

    private function __construct(string $message)
    {
        parent::__construct(message: $message, code: 400);
    }
}
