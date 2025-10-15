<?php

namespace Medpzl\Clubdata\Domain\Model;

class ProgramLinkRel extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * program
     *
     * @var int
     */
    protected $program = 0;

    /**
     * link
     *
     * @var \Medpzl\Clubdata\Domain\Model\Link
     */
    protected $link = null;

    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
    }

    /**
     * Returns the title
     *
     * @return string $title
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

    /**
     * Returns the link
     *
     * @return \Medpzl\Clubdata\Domain\Model\Link $link
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Sets the link
     *
     * @param \Medpzl\Clubdata\Domain\Model\Link $link
     * @return void
     */
    public function setLink(\Medpzl\Clubdata\Domain\Model\Link $link): void
    {
        $this->link = $link;
    }

    /**
     * Returns the program
     *
     * @return int $program
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * Sets the program
     *
     * @param int $program
     * @return void
     */
    public function setProgram($program): void
    {
        $this->program = $program;
    }
}
