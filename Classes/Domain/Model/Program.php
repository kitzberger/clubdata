<?php

namespace Medpzl\Clubdata\Domain\Model;

use DateTime;
use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Program extends AbstractEntity
{
    protected bool $avoidNl = false;
    protected bool $festival = false;
    protected bool $hideDate = false;
    protected bool $highlight = false;
    protected bool $intern = false;
    protected bool $noservice = false;
    protected bool $permHighlight = false;
    protected bool $reduction = false;
    protected bool $seating = false;
    protected int $flags = 0;
    protected int $maxTickets = 0;
    protected int $serviceBarNum = 0;
    protected int $soldTickets = 0;
    protected string $catPriceA = '';
    protected string $catPriceB = '';
    protected string $catPriceC = '';
    protected string $description = '';
    protected string $entrance = '';
    protected string $genre = '';
    protected string $internalInfo = '';
    protected string $preSales = '';
    protected string $priceA = '';
    protected string $priceB = '';
    protected string $priceC = '';
    protected string $secSubTitle = '';
    protected string $slug = '';
    protected string $stateText = '';
    protected string $subTitle = '';
    protected string $ticketLink = '';
    protected string $title = '';
    protected string $venue = '';
    protected string $visitors = '';
    protected ?DateTime $datetime = null;
    protected ?Seating $seatings = null;
    protected ?State $state = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    #[Lazy]
    protected $picture = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Medpzl\Clubdata\Domain\Model\ProgramLink>
     */
    #[Cascade(['value' => 'remove'])]
    protected $links = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Medpzl\Clubdata\Domain\Model\ProgramServiceUser>
     */
    #[Cascade(['value' => 'remove'])]
    protected $services = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Medpzl\Clubdata\Domain\Model\Category>
     */
    #[Lazy]
    protected $categories = null;

    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     */
    protected function initStorageObjects(): void
    {
        $this->links = new ObjectStorage();
        $this->services = new ObjectStorage();
        $this->categories = new ObjectStorage();
    }

    public function isHide(): bool
    {
        return $this->hide;
    }

    public function getIntern(): bool
    {
        return $this->intern;
    }

    public function setIntern(bool $intern): void
    {
        $this->intern = $intern;
    }

    public function isIntern(): bool
    {
        return $this->intern;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDatetime(): ?\DateTime
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTime $datetime): void
    {
        $this->datetime = $datetime;
    }

    public function getIsUpcoming(): bool
    {
        return $this->datetime->format('U') > time();
    }

    public function getHideDate(): bool
    {
        return $this->hideDate;
    }

    public function setHideDate(bool $hideDate): void
    {
        $this->hideDate = $hideDate;
    }

    public function isHideDate(): bool
    {
        return $this->hideDate;
    }

    public function getSubTitle(): string
    {
        return $this->subTitle;
    }

    public function setSubTitle(string $subTitle): void
    {
        $this->subTitle = $subTitle;
    }

    public function getSecSubTitle(): string
    {
        return $this->secSubTitle;
    }

    public function setSecSubTitle(string $secSubTitle): void
    {
        $this->secSubTitle = $secSubTitle;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getHighlight(): bool
    {
        return $this->highlight;
    }

    public function setHighlight(bool $highlight): void
    {
        $this->highlight = $highlight;
    }

    public function isHighlight(): bool
    {
        return $this->highlight;
    }

    public function getPermHighlight(): bool
    {
        return $this->permHighlight;
    }

    public function setPermHighlight(bool $permHighlight): void
    {
        $this->permHighlight = $permHighlight;
    }

    public function isPermHighlight(): bool
    {
        return $this->permHighlight;
    }

    public function getCatPriceA(): string
    {
        return $this->catPriceA;
    }

    public function setCatPriceA(string $catPriceA): void
    {
        $this->catPriceA = $catPriceA;
    }

    public function getPriceA(): string
    {
        return $this->priceA;
    }

    public function setPriceA(string $priceA): void
    {
        $this->priceA = $priceA;
    }

    public function getCatPriceB(): string
    {
        return $this->catPriceB;
    }

    public function setCatPriceB(string $catPriceB): void
    {
        $this->catPriceB = $catPriceB;
    }

    public function getPriceB(): string
    {
        return $this->priceB;
    }

    public function setPriceB(string $priceB): void
    {
        $this->priceB = $priceB;
    }

    public function getCatPriceC(): string
    {
        return $this->catPriceC;
    }

    public function setCatPriceC(string $catPriceC): void
    {
        $this->catPriceC = $catPriceC;
    }

    public function getPriceC(): string
    {
        return $this->priceC;
    }

    public function setPriceC(string $priceC): void
    {
        $this->priceC = $priceC;
    }

    public function getPicture(): ?ObjectStorage
    {
        return $this->picture;
    }

    public function setPicture(ObjectStorage $picture): void
    {
        $this->picture = $picture;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState($state): void
    {
        $this->state = $state;
    }

    public function getSeating(): bool
    {
        return $this->seating;
    }

    public function setSeating(bool $seating): void
    {
        $this->seating = $seating;
    }

    public function isSeating(): bool
    {
        return $this->seating;
    }

    public function getInternalInfo(): string
    {
        return $this->internalInfo;
    }

    public function setInternalInfo(string $internalInfo): void
    {
        $this->internalInfo = $internalInfo;
    }

    public function getVisitors(): string
    {
        return $this->visitors;
    }

    public function setVisitors(string $visitors): void
    {
        $this->visitors = $visitors;
    }

    public function getEntrance(): string
    {
        return $this->entrance;
    }

    public function setEntrance(string $entrance): void
    {
        $this->entrance = $entrance;
    }

    public function getPreSales(): string
    {
        return $this->preSales;
    }

    public function setPreSales(string $preSales): void
    {
        $this->preSales = $preSales;
    }

    public function getTicketLink(): string
    {
        return $this->ticketLink;
    }

    public function setTicketLink(string $ticketLink): void
    {
        $this->ticketLink = $ticketLink;
    }

    public function getAvoidNl(): bool
    {
        return $this->avoidNl;
    }

    public function setAvoidNl(bool $avoidNl): void
    {
        $this->avoidNl = $avoidNl;
    }

    public function isAvoidNl(): bool
    {
        return $this->avoidNl;
    }

    public function addLink(ProgramLink $link): void
    {
        $this->links->attach($link);
    }

    public function removeLink(ProgramLink $linkToRemove): void
    {
        $this->links->detach($linkToRemove);
    }

    public function getLinks(): ObjectStorage
    {
        return $this->links;
    }

    public function setLinks(ObjectStorage $links): void
    {
        $this->links = $links;
    }

    public function addService(ProgramServiceUser $service): void
    {
        $this->services->attach($service);
    }

    public function removeService(ProgramServiceUser $serviceToRemove): void
    {
        $this->services->detach($serviceToRemove);
    }

    public function getServices(): ObjectStorage
    {
        return $this->services;
    }

    public function setServices(ObjectStorage $services): void
    {
        $this->services = $services;
    }

    public function addCategory(Category $category): void
    {
        $this->categories->attach($category);
    }

    public function removeCategory(Category $categoryToRemove): void
    {
        $this->categories->detach($categoryToRemove);
    }

    public function getCategories(): ObjectStorage
    {
        return $this->categories;
    }

    public function setCategories(ObjectStorage $categories): void
    {
        $this->categories = $categories;
    }

    public function getVenue(): string
    {
        return $this->venue;
    }

    public function setVenue(string $venue): void
    {
        $this->venue = $venue;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getMaxTickets(): int
    {
        return $this->maxTickets;
    }

    public function setMaxTickets(int $maxTickets): void
    {
        $this->maxTickets = $maxTickets;
    }

    public function getSoldTickets(): int
    {
        return $this->soldTickets;
    }

    public function setSoldTickets(int $soldTickets): void
    {
        $this->soldTickets = $soldTickets;
    }

    public function getServiceBarNum(): int
    {
        return $this->serviceBarNum;
    }

    public function setServiceBarNum(int $serviceBarNum): void
    {
        $this->serviceBarNum = $serviceBarNum;
    }

    public function getStateText(): string
    {
        return $this->stateText;
    }

    public function setStateText(string $stateText): void
    {
        $this->stateText = $stateText;
    }

    public function getSeatings(): ?Seating
    {
        return $this->seatings;
    }

    public function setSeatings(Seating $seatings): void
    {
        $this->seatings = $seatings;
    }

    public function getReduction(): bool
    {
        return $this->reduction;
    }

    public function setReduction(bool $reduction): void
    {
        $this->reduction = $reduction;
    }

    public function isReduction(): bool
    {
        return $this->reduction;
    }

    public function getFestival(): bool
    {
        return $this->festival;
    }

    public function setFestival(bool $festival): void
    {
        $this->festival = $festival;
    }

    public function isFestival(): bool
    {
        return $this->festival;
    }

    public function getnoservice(): bool
    {
        return $this->noservice;
    }

    public function setnoservice(bool $noservice): void
    {
        $this->noservice = $noservice;
    }

    public function isnoservice(): bool
    {
        return $this->noservice;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): void
    {
        $this->genre = $genre;
    }

    public function getFlags(): int
    {
        return $this->flags;
    }

    public function setFlags(int $flags): void
    {
        $this->flags = $flags;
    }
}
