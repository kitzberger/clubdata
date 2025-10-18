<?php

namespace Medpzl\Clubdata\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class FrontendUserRepository extends Repository
{
    public function findByGroup(int $groupId): array|QueryResultInterface
    {
        $query = $this->createQuery();

        // usergroup is a comma separate list of uids of fe_groups
        $query->matching($query->contains('usergroup', $groupId));

        $result = $query->execute();
        return $result;
    }
}
