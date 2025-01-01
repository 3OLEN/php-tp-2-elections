<?php

declare(strict_types=1);

namespace TpElections\Utils\Controller;

use TpElections\Exception\Controller\RequiredSelectedGroupException;
use TpElections\Model\Entity\Groupe;
use TpElections\Web\Session\Provider\GroupSessionProvider;

class GroupControllerUtil
{
    public static function getSelectedGroupOrRejectAction(?string $actionName = null): Groupe
    {
        $selectedGroup = GroupSessionProvider::findSelectedGroup();
        if ($selectedGroup === null) {
            if ($actionName !== null) {
                throw RequiredSelectedGroupException::createForAction(action: $actionName);
            }

            throw RequiredSelectedGroupException::createForResourceAccess();
        }

        return $selectedGroup;
    }
}
