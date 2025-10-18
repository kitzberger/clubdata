<?php

namespace Medpzl\Clubdata\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/***************************************************************
*
*  Copyright notice
*
*  (c) 2016
*
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 3 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Custom FrontendUser domain model for clubdata extension
 * Maps to fe_users table with only the fields used by this extension
 */
class FrontendUser extends AbstractEntity
{
    /**
     * Username
     *
     * @var string
     */
    protected $username = '';

    /**
     * First name
     *
     * @var string
     */
    protected $firstName = '';

    /**
     * Last name
     *
     * @var string
     */
    protected $lastName = '';

    /**
     * Address (used to store various data in the extension)
     *
     * @var string
     */
    protected $address = '';

    /**
     * E-Mail
     *
     * @var string
     */
    protected $email = '';

    /**
     * Title
     *
     * @var string
     */
    protected $title = '';

    /**
     * Salutation
     *
     * @var string
     */
    protected $salutation = '';

    /**
     * Company
     *
     * @var string
     */
    protected $company = '';

    /**
     * Zip
     *
     * @var string
     */
    protected $zip = '';

    /**
     * City
     *
     * @var string
     */
    protected $city = '';

    /**
     * Telephone
     *
     * @var string
     */
    protected $telephone = '';

    /**
     * User groups
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Medpzl\Clubdata\Domain\Model\FrontendUserGroup>
     */
    #[\TYPO3\CMS\Extbase\Annotation\ORM\Lazy]
    protected $usergroup;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     *
     * @return void
     */
    protected function initStorageObjects(): void
    {
        $this->usergroup = new ObjectStorage();
    }

    /**
     * Returns the username
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Sets the username
     *
     * @param string $username
     * @return void
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * Returns the combined first and last name
     *
     * @return string
     */
    public function getName(): string
    {
        $name = trim($this->firstName . ' ' . $this->lastName);
        return $name ?: $this->username;
    }

    /**
     * Returns the combined first and last name
     *
     * @return string
     */
    public function getNameReversed(): string
    {
        $name = trim($this->lastName . ', ' . $this->firstName, ', ');
        return $name ?: $this->username;
    }

    /**
     * Returns the firstName
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Sets the firstName
     *
     * @param string $firstName
     * @return void
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * Returns the lastName
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Sets the lastName
     *
     * @param string $lastName
     * @return void
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * Returns the address
     *
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * Sets the address
     *
     * @param string $address
     * @return void
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * Returns the email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Sets the email
     *
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Returns the title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Returns the salutation
     *
     * @return string
     */
    public function getSalutation(): string
    {
        return $this->salutation;
    }

    /**
     * Sets the salutation
     *
     * @param string $salutation
     * @return void
     */
    public function setSalutation(string $salutation): void
    {
        $this->salutation = $salutation;
    }

    /**
     * Returns the company
     *
     * @return string
     */
    public function getCompany(): string
    {
        return $this->company;
    }

    /**
     * Sets the company
     *
     * @param string $company
     * @return void
     */
    public function setCompany(string $company): void
    {
        $this->company = $company;
    }

    /**
     * Returns the zip
     *
     * @return string
     */
    public function getZip(): string
    {
        return $this->zip;
    }

    /**
     * Sets the zip
     *
     * @param string $zip
     * @return void
     */
    public function setZip(string $zip): void
    {
        $this->zip = $zip;
    }

    /**
     * Returns the city
     *
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Sets the city
     *
     * @param string $city
     * @return void
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * Returns the telephone
     *
     * @return string
     */
    public function getTelephone(): string
    {
        return $this->telephone;
    }

    /**
     * Sets the telephone
     *
     * @param string $telephone
     * @return void
     */
    public function setTelephone(string $telephone): void
    {
        $this->telephone = $telephone;
    }

    /**
     * Adds a usergroup
     *
     * @param \Medpzl\Clubdata\Domain\Model\FrontendUserGroup $usergroup
     * @return void
     */
    public function addUsergroup(FrontendUserGroup $usergroup): void
    {
        $this->usergroup->attach($usergroup);
    }

    /**
     * Removes a usergroup
     *
     * @param \Medpzl\Clubdata\Domain\Model\FrontendUserGroup $usergroupToRemove
     * @return void
     */
    public function removeUsergroup(FrontendUserGroup $usergroupToRemove): void
    {
        $this->usergroup->detach($usergroupToRemove);
    }

    /**
     * Returns the usergroup
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Medpzl\Clubdata\Domain\Model\FrontendUserGroup>
     */
    public function getUsergroup(): ObjectStorage
    {
        return $this->usergroup;
    }

    /**
     * Sets the usergroup
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Medpzl\Clubdata\Domain\Model\FrontendUserGroup> $usergroup
     * @return void
     */
    public function setUsergroup(ObjectStorage $usergroup): void
    {
        $this->usergroup = $usergroup;
    }
}
