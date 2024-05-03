<?php

namespace App\Service;

use App\Entity\User;

class RemoveRolesOnBlock
{

    public function resetRoleOnMatch(array $ids, User $user)
    {
        $id = $user->getId();

        if (in_array($id, $ids)) {
            $user->setRoles(["ROLE_USER"]);
            return true;
        }
    }
}
