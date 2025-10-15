<?php

namespace Medpzl\Clubdata\Domain\Model;

class Program extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * intern
     *
     * @var bool
     */
    protected $intern = false;

    /**
     * avoidNl
     *
     * @var bool
     */
    protected $avoidNl = false;

    /**
     * subTitle
     *
     * @var string
     */
    protected $subTitle = '';

    /**
     * secSubTitle
     *
     * @var string
     */
    protected $secSubTitle = '';

    /**
     * datetime
     *
     * @var \DateTime
     */
    protected $datetime = null;

    /**
     * hideDate
     *
     * @var bool
     */
    protected $hideDate = false;

    /**
     * entrance
     *
     * @var string
     */
    protected $entrance = '';

    /**
     * description
     *
     * @var string
     */
    protected $description = '';

    /**
     * seating
     *
     * @var bool
     */
    protected $seating = false;

    /**
     * venue
     *
     * @var string
     */
    protected $venue = '';

    /**
     * slug
     *
     * @var string
     */
    protected $slug = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    #[\TYPO3\CMS\Extbase\Annotation\ORM\Lazy]
    protected $picture = null;

    /**
     * highlight
     *
     * @var bool
     */
    protected $highlight = false;

    /**
     * permHighlight
     *
     * @var bool
     */
    protected $permHighlight = false;

    /**
     * catPriceA
     *
     * @var string
     */
    protected $catPriceA = '';

    /**
     * priceA
     *
     * @var string
     */
    protected $priceA = '';

    /**
     * catPriceB
     *
     * @var string
     */
    protected $catPriceB = '';

    /**
     * priceB
     *
     * @var string
     */
    protected $priceB = '';

    /**
     * catPriceC
     *
     * @var string
     */
    protected $catPriceC = '';

    /**
     * priceC
     *
     * @var string
     */
    protected $priceC = '';

    /**
     * ticketLink
     *
     * @var string
     */
    protected $ticketLink = '';

    /**
     * preSales
     *
     * @var string
     */
    protected $preSales = '';

    /**
     * internalInfo
     *
     * @var string
     */
    protected $internalInfo = '';

    /**
     * visitors
     *
     * @var string
     */
    protected $visitors = '';

    /**
     * state
     *
     * @var \Medpzl\Clubdata\Domain\Model\State
     */
    protected $state = null;

    /**
     * links
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Medpzl\Clubdata\Domain\Model\ProgramLinkRel>
     */
    #[\TYPO3\CMS\Extbase\Annotation\ORM\Cascade(['value' => 'remove'])]
    protected $links = null;

    /**
     * services
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Medpzl\Clubdata\Domain\Model\ProgramServiceRel>
     */
    #[\TYPO3\CMS\Extbase\Annotation\ORM\Cascade(['value' => 'remove'])]
    protected $services = null;

    /**
     * categories
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
     *
     */
    #[\TYPO3\CMS\Extbase\Annotation\ORM\Lazy]
    protected $categories = null;

    /**
     * maxTickets
     *
     * @var int
     */
    protected $maxTickets = 0;

    /**
     * soldTickets
     *
     * @var int
     */
    protected $soldTickets = 0;

    /**
     * serviceBarNum
     *
     * @var int
     */
    protected $serviceBarNum = 0;

    /**
     * stateText
     *
     * @var string
     */
    protected $stateText = '';

    /**
     * seatings
     *
     * @var \Medpzl\Clubdata\Domain\Model\Seating
     */
    protected $seatings = null;

    /**
     * reduction
     *
     * @var bool
     */
    protected $reduction = false;

    /**
     * genre
     *
     * @var string
     */
    protected $genre = '';

    /**
     * festival
     *
     * @var bool
     */
    protected $festival = false;

    /**
     * noservice
     *
     * @var bool
     */
    protected $noservice = false;

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
        $this->links = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->services = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->categories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }
    /**
     * Returns the boolean state of intern
     *
     * @return bool
     */
    public function isHide()
    {
        return $this->hide;
    }

    /**
     * Returns the intern
     *
     * @return bool $intern
     */
    public function getIntern()
    {
        return $this->intern;
    }

    /**
     * Sets the intern
     *
     * @param bool $intern
     * @return void
     */
    public function setIntern($intern): void
    {
        $this->intern = $intern;
    }

    /**
     * Returns the boolean state of intern
     *
     * @return bool
     */
    public function isIntern()
    {
        return $this->intern;
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
     * Returns the datetime
     *
     * @return \DateTime $datetime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Sets the datetime
     *
     * @param \DateTime $datetime
     * @return void
     */
    public function setDatetime(\DateTime $datetime): void
    {
        $this->datetime = $datetime;
    }

    /**
     * Returns the hideDate
     *
     * @return bool $hideDate
     */
    public function getHideDate()
    {
        return $this->hideDate;
    }

    /**
     * Sets the hideDate
     *
     * @param bool $hideDate
     * @return void
     */
    public function setHideDate($hideDate): void
    {
        $this->hideDate = $hideDate;
    }

    /**
     * Returns the boolean state of hideDate
     *
     * @return bool
     */
    public function isHideDate()
    {
        return $this->hideDate;
    }

    /**
     * Returns the subTitle
     *
     * @return string $subTitle
     */
    public function getSubTitle()
    {
        return $this->subTitle;
    }

    /**
     * Sets the subTitle
     *
     * @param string $subTitle
     * @return void
     */
    public function setSubTitle($subTitle): void
    {
        $this->subTitle = $subTitle;
    }

    /**
     * Returns the secSubTitle
     *
     * @return string $secSubTitle
     */
    public function getSecSubTitle()
    {
        return $this->secSubTitle;
    }

    /**
     * Sets the secSubTitle
     *
     * @param string $secSubTitle
     * @return void
     */
    public function setSecSubTitle($secSubTitle): void
    {
        $this->secSubTitle = $secSubTitle;
    }

    /**
     * Returns the description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * Returns the highlight
     *
     * @return bool $highlight
     */
    public function getHighlight()
    {
        return $this->highlight;
    }

    /**
     * Sets the highlight
     *
     * @param bool $highlight
     * @return void
     */
    public function setHighlight($highlight): void
    {
        $this->highlight = $highlight;
    }

    /**
     * Returns the boolean state of highlight
     *
     * @return bool
     */
    public function isHighlight()
    {
        return $this->highlight;
    }

    /**
     * Returns the permHighlight
     *
     * @return bool $permHighlight
     */
    public function getPermHighlight()
    {
        return $this->permHighlight;
    }

    /**
     * Sets the permHighlight
     *
     * @param bool $permHighlight
     * @return void
     */
    public function setPermHighlight($permHighlight): void
    {
        $this->permHighlight = $permHighlight;
    }

    /**
     * Returns the boolean state of permHighlight
     *
     * @return bool
     */
    public function isPermHighlight()
    {
        return $this->permHighlight;
    }

    /**
     * Returns the catPriceA
     *
     * @return string $catPriceA
     */
    public function getCatPriceA()
    {
        return $this->catPriceA;
    }

    /**
     * Sets the catPriceA
     *
     * @param string $catPriceA
     * @return void
     */
    public function setCatPriceA($catPriceA): void
    {
        $this->catPriceA = $catPriceA;
    }

    /**
     * Returns the priceA
     *
     * @return string $priceA
     */
    public function getPriceA()
    {
        return $this->priceA;
    }

    /**
     * Sets the priceA
     *
     * @param string $priceA
     * @return void
     */
    public function setPriceA($priceA): void
    {
        $this->priceA = $priceA;
    }

    /**
     * Returns the catPriceB
     *
     * @return string $catPriceB
     */
    public function getCatPriceB()
    {
        return $this->catPriceB;
    }

    /**
     * Sets the catPriceB
     *
     * @param string $catPriceB
     * @return void
     */
    public function setCatPriceB($catPriceB): void
    {
        $this->catPriceB = $catPriceB;
    }

    /**
     * Returns the priceB
     *
     * @return string $priceB
     */
    public function getPriceB()
    {
        return $this->priceB;
    }

    /**
     * Sets the priceB
     *
     * @param string $priceB
     * @return void
     */
    public function setPriceB($priceB): void
    {
        $this->priceB = $priceB;
    }

    /**
     * Returns the catPriceC
     *
     * @return string $catPriceC
     */
    public function getCatPriceC()
    {
        return $this->catPriceC;
    }

    /**
     * Sets the catPriceC
     *
     * @param string $catPriceC
     * @return void
     */
    public function setCatPriceC($catPriceC): void
    {
        $this->catPriceC = $catPriceC;
    }

    /**
     * Returns the priceC
     *
     * @return string $priceC
     */
    public function getPriceC()
    {
        return $this->priceC;
    }

    /**
     * Sets the priceC
     *
     * @param string $priceC
     * @return void
     */
    public function setPriceC($priceC): void
    {
        $this->priceC = $priceC;
    }

    /**
     * Returns the picture
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage $picture
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Sets the picture
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $picture
     * @return void
     */
    public function setPicture(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $picture): void
    {
        $this->picture = $picture;
    }

    /**
     * Returns the state
     *
     * @return \Medpzl\Clubdata\Domain\Model\State state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets the state
     *
     * @param string $state
     * @return void
     */
    public function setState($state): void
    {
        $this->state = $state;
    }

    /**
     * Returns the seating
     *
     * @return bool $seating
     */
    public function getSeating()
    {
        return $this->seating;
    }

    /**
     * Sets the seating
     *
     * @param bool $seating
     * @return void
     */
    public function setSeating($seating): void
    {
        $this->seating = $seating;
    }

    /**
     * Returns the boolean state of seating
     *
     * @return bool
     */
    public function isSeating()
    {
        return $this->seating;
    }

    /**
     * Returns the internalInfo
     *
     * @return string $internalInfo
     */
    public function getInternalInfo()
    {
        return $this->internalInfo;
    }

    /**
     * Sets the internalInfo
     *
     * @param string $internalInfo
     * @return void
     */
    public function setInternalInfo($internalInfo): void
    {
        $this->internalInfo = $internalInfo;
    }

    /**
     * Returns the visitors
     *
     * @return string $visitors
     */
    public function getVisitors()
    {
        return $this->visitors;
    }

    /**
     * Sets the visitors
     *
     * @param string $visitors
     * @return void
     */
    public function setVisitors($visitors): void
    {
        $this->visitors = $visitors;
    }

    /**
     * Returns the entrance
     *
     * @return string entrance
     */
    public function getEntrance()
    {
        return $this->entrance;
    }

    /**
     * Sets the entrance
     *
     * @param string $entrance
     * @return void
     */
    public function setEntrance($entrance): void
    {
        $this->entrance = $entrance;
    }

    /**
     * Returns the preSales
     *
     * @return string $preSales
     */
    public function getPreSales()
    {
        return $this->preSales;
    }

    /**
     * Sets the preSales
     *
     * @param string $preSales
     * @return void
     */
    public function setPreSales($preSales): void
    {
        $this->preSales = $preSales;
    }

    /**
     * Returns the ticketLink
     *
     * @return string $ticketLink
     */
    public function getTicketLink()
    {
        return $this->ticketLink;
    }

    /**
     * Sets the ticketLink
     *
     * @param string $ticketLink
     * @return void
     */
    public function setTicketLink($ticketLink): void
    {
        $this->ticketLink = $ticketLink;
    }

    /**
     * Returns the avoidNl
     *
     * @return bool $avoidNl
     */
    public function getAvoidNl()
    {
        return $this->avoidNl;
    }

    /**
     * Sets the avoidNl
     *
     * @param bool $avoidNl
     * @return void
     */
    public function setAvoidNl($avoidNl): void
    {
        $this->avoidNl = $avoidNl;
    }

    /**
     * Returns the boolean state of avoidNl
     *
     * @return bool
     */
    public function isAvoidNl()
    {
        return $this->avoidNl;
    }



    /**
     * Adds a ProgramLinkRel
     *
     * @param \Medpzl\Clubdata\Domain\Model\ProgramLinkRel $link
     * @return void
     */
    public function addLink(\Medpzl\Clubdata\Domain\Model\ProgramLinkRel $link): void
    {
        $this->links->attach($link);
    }

    /**
     * Removes a ProgramLinkRel
     *
     * @param \Medpzl\Clubdata\Domain\Model\ProgramLinkRel $linkToRemove The ProgramLinkRel to be removed
     * @return void
     */
    public function removeLink(\Medpzl\Clubdata\Domain\Model\ProgramLinkRel $linkToRemove): void
    {
        $this->links->detach($linkToRemove);
    }

    /**
     * Returns the links
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Medpzl\Clubdata\Domain\Model\ProgramLinkRel> $links
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Sets the links
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Medpzl\Clubdata\Domain\Model\ProgramLinkRel> $links
     * @return void
     */
    public function setLinks(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $links): void
    {
        $this->links = $links;
    }

    /**
     * Adds a ProgramServiceRel
     *
     * @param \Medpzl\Clubdata\Domain\Model\ProgramServiceRel $service
     * @return void
     */
    public function addService(\Medpzl\Clubdata\Domain\Model\ProgramServiceRel $service): void
    {
        $this->services->attach($service);
    }

    /**
     * Removes a ProgramServiceRel
     *
     * @param \Medpzl\Clubdata\Domain\Model\ProgramServiceRel $serviceToRemove The ProgramServiceRel to be removed
     * @return void
     */
    public function removeService(\Medpzl\Clubdata\Domain\Model\ProgramServiceRel $serviceToRemove): void
    {
        $this->services->detach($serviceToRemove);
    }

    /**
     * Returns the services
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Medpzl\Clubdata\Domain\Model\ProgramServiceRel> $services
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Sets the services
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Medpzl\Clubdata\Domain\Model\ProgramServiceRel> $services
     * @return void
     */
    public function setServices(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $services): void
    {
        $this->services = $services;
    }

    /**
     * Adds a Category
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\Category $category
     * @return void
     */
    public function addCategory(\TYPO3\CMS\Extbase\Domain\Model\Category $category): void
    {
        $this->categories->attach($category);
    }

    /**
     * Removes a Category
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\Category $categoryToRemove The Category to be removed
     * @return void
     */
    public function removeCategory(\TYPO3\CMS\Extbase\Domain\Model\Category $categoryToRemove): void
    {
        $this->categories->detach($categoryToRemove);
    }

    /**
     * Returns the categories
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category> $categories
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Sets the categories
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category> $categories
     * @return void
     */
    public function setCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories): void
    {
        $this->categories = $categories;
    }

    /**
     * Returns the venue
     *
     * @return string $venue
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * Sets the venue
     *
     * @param string $venue
     * @return void
     */
    public function setVenue($venue): void
    {
        $this->venue = $venue;
    }

    /**
     * Returns the slug
     *
     * @return string $slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Sets the slug
     *
     * @param string $slug
     * @return void
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    /**
     * Returns the maxTickets
     *
     * @return int $maxTickets
     */
    public function getMaxTickets()
    {
        return $this->maxTickets;
    }

    /**
     * Sets the maxTickets
     *
     * @param int $maxTickets
     * @return void
     */
    public function setMaxTickets($maxTickets): void
    {
        $this->maxTickets = $maxTickets;
    }

    /**
     * Returns the soldTickets
     *
     * @return int $soldTickets
     */
    public function getSoldTickets()
    {
        return $this->soldTickets;
    }

    /**
     * Sets the soldTickets
     *
     * @param int $soldTickets
     * @return void
     */
    public function setSoldTickets($soldTickets): void
    {
        $this->soldTickets = $soldTickets;
    }

    /**
     * Returns the serviceBarNum
     *
     * @return int $serviceBarNum
     */
    public function getServiceBarNum()
    {
        return $this->serviceBarNum;
    }

    /**
     * Sets the serviceBarNum
     *
     * @param int $serviceBarNum
     * @return void
     */
    public function setServiceBarNum($serviceBarNum): void
    {
        $this->serviceBarNum = $serviceBarNum;
    }

    /**
     * Returns the stateText
     *
     * @return string $stateText
     */
    public function getStateText()
    {
        return $this->stateText;
    }

    /**
     * Sets the stateText
     *
     * @param string $stateText
     * @return void
     */
    public function setStateText($stateText): void
    {
        $this->stateText = $stateText;
    }

    /**
     * Returns the seatings
     *
     * @return \Medpzl\Clubdata\Domain\Model\Seating $seatings
     */
    public function getSeatings()
    {
        return $this->seatings;
    }

    /**
     * Sets the seatings
     *
     * @param \Medpzl\Clubdata\Domain\Model\Seating $seatings
     * @return void
     */
    public function setSeatings(\Medpzl\Clubdata\Domain\Model\Seating $seatings): void
    {
        $this->seatings = $seatings;
    }

    /**
     * Returns the reduction
     *
     * @return bool $reduction
     */
    public function getReduction()
    {
        return $this->reduction;
    }

    /**
     * Sets the reduction
     *
     * @param bool $reduction
     * @return void
     */
    public function setReduction($reduction): void
    {
        $this->reduction = $reduction;
    }

    /**
     * Returns the boolean state of reduction
     *
     * @return bool
     */
    public function isReduction()
    {
        return $this->reduction;
    }

    /**
     * Returns the festival
     *
     * @return bool $festival
     */
    public function getFestival()
    {
        return $this->festival;
    }

    /**
     * Sets the festival
     *
     * @param bool $festival
     * @return void
     */
    public function setFestival($festival): void
    {
        $this->festival = $festival;
    }

    /**
     * Returns the boolean state of festival
     *
     * @return bool
     */
    public function isFestival()
    {
        return $this->festival;
    }

    /**
     * Returns the noservice
     *
     * @return bool $noservice
     */
    public function getnoservice()
    {
        return $this->noservice;
    }

    /**
     * Sets the noservice
     *
     * @param bool $noservice
     * @return void
     */
    public function setnoservice($noservice): void
    {
        $this->noservice = $noservice;
    }

    /**
     * Returns the boolean state of noservice
     *
     * @return bool
     */
    public function isnoservice()
    {
        return $this->noservice;
    }

    /**
     * Returns the genre
     *
     * @return string $genre
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Sets the genre
     *
     * @param string $genre
     * @return void
     */
    public function setGenre($genre): void
    {
        $this->genre = $genre;
    }
}
