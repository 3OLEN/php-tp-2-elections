<?php

declare(strict_types=1);

namespace TpElections\Web\Session\Provider;

class MessageSessionProvider
{
    private const string MESSAGE_FOR_NEXT_ACTION_SESSION_KEY = 'next_action_message';

    public static function defineMessageForNextAction(string $message): void
    {
        $_SESSION[static::MESSAGE_FOR_NEXT_ACTION_SESSION_KEY] = $message;
    }

    public static function hasMessageForNextAction(): bool
    {
        return is_string($_SESSION[static::MESSAGE_FOR_NEXT_ACTION_SESSION_KEY] ?? null);
    }

    public static function getMessageForNextAction(): string
    {
        $message = $_SESSION[static::MESSAGE_FOR_NEXT_ACTION_SESSION_KEY] ?? '';
        unset($_SESSION[static::MESSAGE_FOR_NEXT_ACTION_SESSION_KEY]);

        return $message;
    }
}
