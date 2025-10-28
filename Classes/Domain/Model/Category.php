<?php

declare(strict_types=1);

namespace Medpzl\Clubdata\Domain\Model;

use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Category extends \TYPO3\CMS\Extbase\Domain\Model\Category
{
    /** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Medpzl\Clubdata\Domain\Model\Category> */
    protected $children = null;

    public function __construct()
    {
        $this->initStorageObjects();
    }

    /** Initializes all ObjectStorage properties */
    protected function initStorageObjects(): void
    {
        $this->children = new ObjectStorage();
    }

    public function getChildren(): ?ObjectStorage
    {
        if ($this->children instanceof LazyLoadingProxy) {
            $this->children->_loadRealInstance();
        }
        return $this->children;
    }

    public function setChildren(ObjectStorage $children): void
    {
        $this->children = $children;
    }

    public function addChild(Category $child): void
    {
        $this->children->attach($child);
    }

    public function removeChild(Category $child): void
    {
        $this->children->detach($child);
    }

    public function hasChildren(): bool
    {
        return $this->children->count() > 0;
    }
}
