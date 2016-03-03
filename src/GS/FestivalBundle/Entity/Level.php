<?php

namespace GS\FestivalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Level
 *
 * @ORM\Table(name="level")
 * @ORM\Entity(repositoryClass="GS\FestivalBundle\Repository\LevelRepository")
 */
class Level
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="solo", type="boolean")
     */
    private $solo = False;

    /**
     * @var int
     *
     * @ORM\Column(name="capacity", type="smallint")
     */
    private $capacity;

    /**
     * @var int
     *
     * @ORM\Column(name="extraPerson", type="smallint")
     */
    private $extraPerson;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="GS\FestivalBundle\Entity\Festival", inversedBy="levels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $festival;

    /**
     * @ORM\OneToMany(targetEntity="GS\FestivalBundle\Entity\Registration", mappedBy="level", cascade={"persist", "remove"})
     */
    private $registrations;

    /**
     * @ORM\Column(name="nbRegistrations", type="integer")
     */
    private $nbRegistrations = 0;

    public function increaseRegistration()
    {
        $this->nbRegistrations++;
    }

    public function decreaseRegistration()
    {
        $this->nbRegistrations--;
    }

    public function addRegistration(Registration $registration)
    {
        $this->registrations[] = $registration;
        $registration->setLevel($this);
        return $this;
    }

    public function removeRegistration(Registration $registration)
    {
        $this->registrations->removeElement($registration);
    }

    public function getRegistrations()
    {
        return $this->registrations;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Level
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Level
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set solo
     *
     * @param boolean $solo
     *
     * @return Level
     */
    public function setSolo($solo)
    {
        $this->solo = $solo;

        return $this;
    }

    /**
     * Get solo
     *
     * @return bool
     */
    public function getSolo()
    {
        return $this->solo;
    }

    /**
     * Set capacity
     *
     * @param integer $capacity
     *
     * @return Level
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * Get capacity
     *
     * @return int
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Level
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set festival
     *
     * @param \GS\FestivalBundle\Entity\Festival $festival
     *
     * @return Level
     */
    public function setFestival(\GS\FestivalBundle\Entity\Festival $festival)
    {
        $this->festival = $festival;

        return $this;
    }

    /**
     * Get festival
     *
     * @return \GS\FestivalBundle\Entity\Festival
     */
    public function getFestival()
    {
        return $this->festival;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->registrations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set extraPerson
     *
     * @param integer $extraPerson
     *
     * @return Level
     */
    public function setExtraPerson($extraPerson)
    {
        $this->extraPerson = $extraPerson;

        return $this;
    }

    /**
     * Get extraPerson
     *
     * @return integer
     */
    public function getExtraPerson()
    {
        return $this->extraPerson;
    }

    /**
     * Set nbRegistrations
     *
     * @param integer $nbRegistrations
     *
     * @return Level
     */
    public function setNbRegistrations($nbRegistrations)
    {
        $this->nbRegistrations = $nbRegistrations;

        return $this;
    }

    /**
     * Get nbRegistrations
     *
     * @return integer
     */
    public function getNbRegistrations()
    {
        return $this->nbRegistrations;
    }

}
