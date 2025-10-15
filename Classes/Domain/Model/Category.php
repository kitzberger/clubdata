<?php

namespace Medpzl\Clubdata\Domain\Model;

class Category extends \TYPO3\CMS\Extbase\Domain\Model\Category
{
    /**
     * categories
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Medpzl\Clubdata\Domain\Model\Category>
     */
    #[\TYPO3\CMS\Extbase\Annotation\ORM\Lazy]
    protected $categories = null;


    /**
     * Get categories
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Medpzl\Clubdata\Domain\Model\Category>
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set categories
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories
     * @return void
     */
    public function setCategories($categories): void
    {
        $this->categories = $categories;
    }
}
