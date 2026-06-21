<?php

namespace Medpzl\Clubdata\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

class ProgramServiceUserRepository extends Repository
{
    public function findEntry(?int $user = null, ?int $program = null, mixed $service = null)
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
            if (is_array($service)) {
                $and_constraints[] = $query->in('service.uid', $service);
            } else {
                $and_constraints[] = $query->equals('service.uid', $service);
            }
        }
        if ($and_constraints) {
            $query->matching($query->logicalAnd(...$and_constraints));
        }
        $result = $query->execute();
        return $result;
    }
}
