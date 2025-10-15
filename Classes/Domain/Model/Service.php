<?php

namespace Medpzl\Clubdata\Domain\Model;

class Service extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * sorting
     *
     * @var string $sorting
     */
    protected $sorting = null;

    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * Setter for sorting
     *
     * @param string $sorting
     * @return void
     */
    public function setSorting($sorting): void
    {
        $this->sorting = $sorting;
    }

    /**
     * Getter for sorting
     *
     * @return string sorting
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * Returns the title
     *
     * @return string title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }
}
