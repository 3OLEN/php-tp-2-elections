<?php

declare(strict_types=1);

namespace TpElections\Web\Session\Provider;

use TpElections\Model\Entity\Groupe;

class GroupSessionProvider
{
    private const string SELECTED_GROUP_SESSION_KEY = 'selected_group';

    public static function findSelectedGroup(): ?Groupe
    {
        if (
            isset($_SESSION[static::SELECTED_GROUP_SESSION_KEY]) === false
            || $_SESSION[static::SELECTED_GROUP_SESSION_KEY] instanceof Groupe === false
        ) {
            // On le vide de la session, car il s'agit d'une mauvaise valeur
            unset($_SESSION[static::SELECTED_GROUP_SESSION_KEY]);

            return null;
        }

        return $_SESSION[static::SELECTED_GROUP_SESSION_KEY];
    }

    public static function setSelectedGroup(Groupe $group): void
    {
        $_SESSION[static::SELECTED_GROUP_SESSION_KEY] = $group;
    }
}
