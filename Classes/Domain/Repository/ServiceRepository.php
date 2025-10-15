<?php

namespace Medpzl\Clubdata\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class ServiceRepository extends Repository
{
    protected $defaultOrderings = ['sorting' => QueryInterface::ORDER_ASCENDING];
}
