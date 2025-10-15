<?php

namespace Medpzl\Clubdata\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class ProgramRepository extends Repository
{
    public function findWithinMonth($filter = [], $fromTimestamp = null, $toTimestamp = null, $greaternow = 1, $now = '')
    {
        $query = $this->createQuery();
        $query->setOrderings(['datetime' => QueryInterface::ORDER_ASCENDING]);
        if (($filter['sorting'] ?? 'asc') == 'desc') {
            $query->setOrderings(['datetime' => QueryInterface::ORDER_DESCENDING]);
        }

        $and_constraints = [];
        if ($fromTimestamp) {
            $and_constraints[] = $query->greaterThanOrEqual('datetime', $fromTimestamp);
        }
        if ($toTimestamp) {
            $and_constraints[] = $query->lessThanOrEqual('datetime', $toTimestamp);
        }

        $greaternow = intval($greaternow);

        if ($greaternow > 0 and $greaternow < 2) {
            $and_constraints[] = $query->greaterThanOrEqual('datetime', strtotime($now));
        } elseif ($greaternow == 2) {
            $and_constraints[] = $query->lessThan('datetime', strtotime(date('c')));
        }
        if ($filter['hide_avoild_nl'] ?? false) {
            $and_constraints[] = $query->lessThan('avoidNl', 1);
        }
        if ($filter['intern'] ?? false) {
            $and_constraints[] = $query->lessThan('intern', 1);
        }
        if ($filter['images'] ?? false) {
            $and_constraints[] = $query->greaterThan('picture.uid', 0);
        }
        if ($filter['noservice'] ?? false) {
            $and_constraints[] = $query->lessThan('noservice', 1);
        }

        if ($filter['highlight'] ?? false) {
            $query->setOrderings(['permHighlight' => QueryInterface::ORDER_DESCENDING,'datetime' => QueryInterface::ORDER_ASCENDING]);
            $subConstraints = [];
            $subConstraints[] = $query->greaterThan('highlight', 0);
            $subConstraints[] = $query->greaterThan('permHighlight', 0);
            $and_constraints[] = $query->logicalOr(...$subConstraints);
        }

        if ($filter['limit'] ?? false) {
            $query->setLimit(intval($filter['limit']));
        }

        if (!empty($filter['categories']) && ($filter['category_what'] ?? '') != 'all') {
            $subConstraints = [];
            $categories = explode(',', $filter['categories']);
            foreach ($categories as $category) {
                $subConstraints[] = $query->contains('categories', $category);
            }
            if ($subConstraints) {
                if ($filter['category_what'] == 'not') {
                    $and_constraints[] =  $query->logicalNot(
                        $query->in('categories.uid', $categories)
                    );
                } else {
                    $and_constraints[] = $query->logicalOr(...$subConstraints);
                }
            }
        }
        if (!empty($filter['states']) && ($filter['state_what'] ?? '') != 'all') {
            $subConstraints = [];
            $states = explode(',', $filter['states']);
            foreach ($states as $state) {
                $subConstraints[] = $query->equals('state.uid', $state);
            }
            if ($subConstraints) {
                if ($filter['state_what'] == 'not') {
                    $and_constraints[] =  $query->logicalNot(
                        $query->in('state.uid', $state)
                    );
                } else {
                    $and_constraints[] = $query->logicalOr(...$subConstraints);
                }
            }
        }

        if ($and_constraints) {
            $query->matching($query->logicalAnd(...$and_constraints));
        }

        $result = $query->execute();
        return $result;
    }

    public function findUpcoming($filter = [], $greaternow = true, $now = '')
    {
        $query = $this->createQuery();
        $query->setOrderings(['datetime' => QueryInterface::ORDER_ASCENDING]);
        $and_constraints = [];

        if ($filter['limit']) {
            $query->setLimit(intval($filter['limit']));
        }
        if ($greaternow > 0) {
            $and_constraints[] = $query->greaterThanOrEqual('datetime', strtotime($now));
        } elseif ($greaternow < 0) {
            $and_constraints[] = $query->lessThan('datetime', strtotime($now));
        }
        $and_constraints[] = $query->lessThan('intern', 1);

        if ($filter['highlight']) {
            $query->setOrderings([
                'permHighlight' => QueryInterface::ORDER_DESCENDING,
                'datetime' => QueryInterface::ORDER_ASCENDING
            ]);
            $subConstraints = [];
            $subConstraints[] = $query->greaterThan('highlight', 0);
            $subConstraints[] = $query->greaterThan('permHighlight', 0);
            $and_constraints[] = $query->logicalOr(...$subConstraints);
        }

        if ($filter['categories']) {
            $subConstraints = [];
            $categories = explode(',', $filter['categories']);
            foreach ($categories as $category) {
                $subConstraints[] = $query->contains('categories', $category);
            }
            //var_dump($subConstraints);
            if ($subConstraints) {
                if ($filter['category_what'] == 'not') {
                    $and_constraints[] =  $query->logicalNot(
                        $query->in('categories.uid', $categories)
                    );
                } else {
                    $and_constraints[] = $query->logicalOr(...$subConstraints);
                }
            }
        }

        if ($and_constraints) {
            $query->matching($query->logicalAnd(...$and_constraints));
        }

        $result = $query->execute();

        return $result;
    }

    public function findEditList($fromTimestamp, $toTimestamp, $greaternow = false, $sorting = 'desc')
    {
        $query = $this->createQuery();
        $query->setOrderings(['datetime' => QueryInterface::ORDER_DESCENDING]);
        if ($sorting == 'asc') {
            $query->setOrderings(['datetime' => QueryInterface::ORDER_ASCENDING]);
        }
        $and_constraints = [];
        $and_constraints[] = $query->greaterThanOrEqual('datetime', $fromTimestamp);
        $and_constraints[] = $query->lessThanOrEqual('datetime', $toTimestamp);

        if ($greaternow) {
            $and_constraints[] = $query->greaterThanOrEqual('datetime', strtotime(datetime('c')));
        }

        if ($and_constraints) {
            $query->matching($query->logicalAnd(...$and_constraints));
        }
        $result = $query->execute();
        return $result;
    }

    public function findPosterImages($settings = [])
    {
        $query = $this->createQuery();
        $query->setOrderings(['datetime' => QueryInterface::ORDER_ASCENDING]);
        $and_constraints = [];
        $and_constraints[] = $query->greaterThanOrEqual('datetime', datetime('U'));
        if ($settings['showIntern'] != '1') {
            $and_constraints[] = $query->lessThan('intern', 1);
        }
        $and_constraints[] = $query->greaterThan('movie.poster.uid', 0);
        if ($settings['category']) {
            $subConstraints = [];
            $categories = explode(',', $settings['category']);
            foreach ($categories as $category) {
                $subConstraints[] = $query->contains('category', $category);
            }
            $and_constraints[] = $query->logicalOr(...$subConstraints);
        }
        $query->matching($query->logicalAnd(...$and_constraints));
        $result = $query->execute();
        return $result;
    }

    public function findOtherTimes($movieUid, $programUid, $exculde)
    {
        $query = $this->createQuery();
        $query->setOrderings(['datetime' => QueryInterface::ORDER_ASCENDING]);
        $and_constraints = [];
        $and_constraints[] = $query->greaterThanOrEqual('datetime', datetime('U'));
        $and_constraints[] = $query->lessThan('intern', 1);
        $and_constraints[] = $query->equals('movie.uid', $movieUid);
        if ($exculde) {
            $and_constraints[] = $query->logicalNot(
                $query->in('uid', [0 => $programUid])
            );
        }
        $query->matching($query->logicalAnd(...$and_constraints));
        $result = $query->execute();
        return $result;
    }

    public function findProgram($movieUid)
    {
        $query = $this->createQuery();
        $query->setOrderings(['datetime' => QueryInterface::ORDER_ASCENDING]);
        $and_constraints = [];
        $and_constraints[] = $query->greaterThanOrEqual('datetime', datetime('U'));
        $and_constraints[] = $query->lessThan('intern', 1);
        $and_constraints[] = $query->equals('movie.uid', $movieUid);
        $query->matching($query->logicalAnd(...$and_constraints));
        $query->setLimit(1);
        $result = $query->execute();
        return $result;
    }

    public function findLatest()
    {
        $query = $this->createQuery();
        $query->setOrderings(['datetime' => QueryInterface::ORDER_DESCENDING]);
        $query->setLimit(1);
        return $query->execute();
    }

    public function findOldest($greaternow = false)
    {
        $query = $this->createQuery();
        $query->setOrderings(['datetime' => QueryInterface::ORDER_ASCENDING]);
        $and_constraints = [];
        if ($greaternow) {
            $and_constraints[] = $query->greaterThanOrEqual('datetime', strtotime(date('c')));
        }
        if ($and_constraints) {
            $query->matching($query->logicalAnd(...$and_constraints));
        }
        $query->setLimit(1);
        $result = $query->execute();
        return $result;
    }

    /**
     * Find next item by uid
     * @param integer $uid The uid of the current record
     * @return boolean|\TYPO3\CMS\Extbase\Persistence\Generic\QueryResult
     */
    public function findNext($uid)
    {
        $query = $this->createQuery();
        $query->setOrderings(['datetime' => QueryInterface::ORDER_ASCENDING]);
        $result = $query->matching($query->greaterThan('uid', $uid))->setLimit(1)->execute();
        if ($query->count()) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Find previous item by uid
     * @param integer $uid The uid of the current record
     * @return boolean|\TYPO3\CMS\Extbase\Persistence\Generic\QueryResult
     */
    public function findPrev($uid)
    {
        $query = $this->createQuery();
        $query->setOrderings(['datetime' => QueryInterface::ORDER_ASCENDING]);
        $result = $query->matching($query->lessThan('uid', $uid))->setLimit(1)->execute();
        if ($query->count()) {
            return $result;
        } else {
            return false;
        }
    }

    public function findSorted($filter = [], $greaternow = false)
    {
        $query = $this->createQuery();
        $and_constraints = [];
        if ($greaternow) {
            $and_constraints[] = $query->greaterThanOrEqual('datetime', strtotime(date('c')));
        }
        $query->setOrderings(['datetime' => QueryInterface::ORDER_ASCENDING]);
        if ($filter['sorting'] == 'desc') {
            $query->setOrderings(['datetime' => QueryInterface::ORDER_DESCENDING]);
        }
        if ($and_constraints) {
            $query->matching($query->logicalAnd(...$and_constraints));
        }
        $result = $query->execute();
        return $result;
    }
}
