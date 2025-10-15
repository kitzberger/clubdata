<?php

namespace Medpzl\Clubdata\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

class ProgramServiceRepository extends Repository
{
    public function findEntry($user = 0, $program = 0, $service = 0)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $and_constraints = [];
        if ($user) {
            $and_constraints[] = $query->equals('user.uid', $user);
        }
        if ($program) {
            $and_constraints[] = $query->equals('program.uid', $program);
        }
        if ($service) {
            $and_constraints[] = $query->equals('service.uid', $service);
        }
        if ($and_constraints) {
            $query->matching($query->logicalAnd(...$and_constraints));
        }
        $result = $query->execute();
        return $result;
    }
}
