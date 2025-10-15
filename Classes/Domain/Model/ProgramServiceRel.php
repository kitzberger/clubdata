<?php

namespace Medpzl\Clubdata\Domain\Model;

class ProgramServiceRel extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * sorting
     *
     * @var string $sorting
     */
    protected $sorting = null;

    /**
     * name
     *
     * @var string
     */
    protected $name = '';

    /**
     * program
     *
     * @var int
     */
    protected $program = '';

    /**
     * service
     *
     * @var \Medpzl\Clubdata\Domain\Model\Service
     */
    protected $service = null;

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
     * Returns the service
     *
     * @return \Medpzl\Clubdata\Domain\Model\Service $service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Sets the service
     *
     * @param \Medpzl\Clubdata\Domain\Model\Service $service
     * @return void
     */
    public function setService(\Medpzl\Clubdata\Domain\Model\Service $service): void
    {
        $this->service = $service;
    }

    /**
     * Returns the name
     *
     * @return string name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * Returns the program
     *
     * @return int program
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * Sets the program
     *
     * @param string $program
     * @return void
     */
    public function setProgram($program): void
    {
        $this->program = $program;
    }
}
