<?php

namespace Medpzl\Clubdata\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class CategoryRepository extends Repository
{
    protected $defaultOrderings = ['sorting' => QueryInterface::ORDER_ASCENDING];

    public function findChildrenByParent($category = 0, $excludeCategories = [])
    {
        $constraints = [];
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $constraints[] = $query->equals('parent', $category);

        if (count($excludeCategories) > 0) {
            $constraints[] = $query->logicalNot($query->in('uid', $excludeCategories));
        }

        $query->matching($query->logicalAnd(...$constraints));

        return $query->execute();
    }
}
