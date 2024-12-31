<?php

declare(strict_types=1);

namespace TpElections\Web\Session\Provider;

use TpElections\Model\Entity\Groupe;

class GroupSessionProvider
{
    public static function findSelectedGroup(): ?Groupe
    {
        if (
            isset($_SESSION['selected_group']) === false
            || $_SESSION['selected_group'] instanceof Groupe === false
        ) {
            return null;
        }

        return $_SESSION['selected_group'];
    }
}
