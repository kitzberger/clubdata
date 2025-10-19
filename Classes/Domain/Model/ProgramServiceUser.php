<?php

namespace Medpzl\Clubdata\Domain\Model;

class ProgramServiceUser extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    public const OPERATION_NONE = 0;
    public const OPERATION_CHANGE = 1;
    public const OPERATION_DELETE = 2;

    /**
     * remark
     *
     * @var string
     */
    protected $remark = '';

    /**
     * program
     *
     * @var \Medpzl\Clubdata\Domain\Model\Program
     */
    protected $program = null;

    /**
     * service
     *
     * @var \Medpzl\Clubdata\Domain\Model\Service
     */
    protected $service = null;

    /**
     * user
     *
     * @var \Medpzl\Clubdata\Domain\Model\FrontendUser
     */
    protected $user = null;

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
     * Returns the remark
     *
     * @return string $remark
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * Sets the remark
     *
     * @param string $remark
     * @return void
     */
    public function setRemark($remark): void
    {
        $this->remark = $remark;
    }

    /**
     * Returns the program
     *
     * @return \Medpzl\Clubdata\Domain\Model\Program $program
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * Sets the program
     *
     * @param \Medpzl\Clubdata\Domain\Model\Program $program
     * @return void
     */
    public function setProgram(\Medpzl\Clubdata\Domain\Model\Program $program): void
    {
        $this->program = $program;
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
     * Returns the user
     *
     * @return \Medpzl\Clubdata\Domain\Model\FrontendUser $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Sets the user
     *
     * @param \Medpzl\Clubdata\Domain\Model\FrontendUser $user
     * @return void
     */
    public function setUser(FrontendUser $user): void
    {
        $this->user = $user;
    }
}
