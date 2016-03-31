<?php

namespace GS\FestivalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Exclude;

/**
 * Registration
 *
 * @ORM\Table(name="registration")
 * @ORM\Entity(repositoryClass="GS\FestivalBundle\Repository\RegistrationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Registration
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
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status = 'received';

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var bool
     *
     * @ORM\Column(name="role", type="boolean")
     */
    private $role;

    /**
     * @ORM\ManyToOne(targetEntity="GS\FestivalBundle\Entity\Level", inversedBy="registrations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $level;

    /**
     * @ORM\ManyToOne(targetEntity="GS\PersonBundle\Entity\Person", inversedBy="registrations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $person;

    /**
     * @var string
     *
     * @ORM\Column(name="partnerFirstName", type="string", length=255, nullable=true)
     */
    private $partnerFirstName;

    /**
     * @var string
     *
     * @ORM\Column(name="partnerLastName", type="string", length=255, nullable=true)
     */
    private $partnerLastName;

    /**
     * @var string
     *
     * @ORM\Column(name="partnerEmail", type="string", length=255, nullable=true)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    private $partnerEmail;

    /**
     * @var float
     *
     * @ORM\Column(name="remainingPayment", type="float")
     */
    private $remainingPayment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="assignmentDate", type="datetime", nullable=true)
     */
    private $assignmentDate;

    /**
     * @ORM\OneToMany(targetEntity="GS\FestivalBundle\Entity\Payment", mappedBy="registration", cascade={"persist", "remove"})
     */
    private $payments;

    /**
     * @Exclude
     * @ORM\ManyToOne(targetEntity="GS\FestivalBundle\Entity\Registration")
     */
    private $partner;

    /**
     * @ORM\Column(name="offerHousing", type="boolean")
     */
    private $offerHousing = false;

    /**
     * @ORM\Column(name="doubleBeds", type="smallint")
     * @Assert\GreaterThanOrEqual(0)
     */
    private $doubleBeds = 0;

    /**
     * @ORM\Column(name="singleBeds", type="smallint")
     * @Assert\GreaterThanOrEqual(0)
     */
    private $singleBeds = 0;

    /**
     * @ORM\Column(name="requestHousing", type="boolean")
     */
    private $requestHousing = false;

    /**
     * @ORM\Column(name="shareBed", type="string", length=255, nullable=true)
     */
    private $shareBed;

    /**
     * @ORM\Column(name="roommates", type="text", nullable=true)
     */
    private $roommates;

    /**
     * @ORM\Column(name="comments", type="text", nullable=true)
     */
    private $comments;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
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
     * Set status
     *
     * @param string $status
     *
     * @return Registration
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set role
     *
     * @param boolean $role
     *
     * @return Registration
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return bool
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set level
     *
     * @param \GS\FestivalBundle\Entity\Level $level
     *
     * @return Registration
     */
    public function setLevel(\GS\FestivalBundle\Entity\Level $level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return \GS\FestivalBundle\Entity\Level
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @ORM\PrePersist
     */
    public function increase()
    {
        $this->getLevel()->increaseRegistration();
    }

    /**
     * @ORM\PreRemove
     */
    public function decrease()
    {
        $this->getLevel()->decreaseRegistration();
    }

    /**
     * Set person
     *
     * @param \GS\PersonBundle\Entity\Person $person
     *
     * @return Registration
     */
    public function setPerson(\GS\PersonBundle\Entity\Person $person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \GS\PersonBundle\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set partner
     *
     * @param \GS\FestivalBundle\Entity\Person $partner
     *
     * @return Registration
     */
    public function setPartner(\GS\FestivalBundle\Entity\Registration $partner = null)
    {
        $this->partner = $partner;
        if ( null === $partner->getPartner() ) {
            $partner->setPartner($this);
        }

        return $this;
    }

    /**
     * Get partner
     *
     * @return \GS\FestivalBundle\Entity\Registration
     */
    public function getPartner()
    {
        return $this->partner;
    }

    /**
     * Set partnerFirstName
     *
     * @param string $partnerFirstName
     *
     * @return Registration
     */
    public function setPartnerFirstName($partnerFirstName)
    {
        $this->partnerFirstName = $partnerFirstName;

        return $this;
    }

    /**
     * Get partnerFirstName
     *
     * @return string
     */
    public function getPartnerFirstName()
    {
        return $this->partnerFirstName;
    }

    /**
     * Set partnerLastName
     *
     * @param string $partnerLastName
     *
     * @return Registration
     */
    public function setPartnerLastName($partnerLastName)
    {
        $this->partnerLastName = $partnerLastName;

        return $this;
    }

    /**
     * Get partnerLastName
     *
     * @return string
     */
    public function getPartnerLastName()
    {
        return $this->partnerLastName;
    }

    /**
     * Set partnerEmail
     *
     * @param string $partnerEmail
     *
     * @return Registration
     */
    public function setPartnerEmail($partnerEmail)
    {
        $this->partnerEmail = $partnerEmail;

        return $this;
    }

    /**
     * Get partnerEmail
     *
     * @return string
     */
    public function getPartnerEmail()
    {
        return $this->partnerEmail;
    }

    /**
     * Get displayName
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->getPerson()->getDisplayName();
    }

    /**
     * Set remainingPayment
     *
     * @param float $remainingPayment
     *
     * @return Registration
     */
    public function setRemainingPayment($remainingPayment)
    {
        $this->remainingPayment = $remainingPayment;

        return $this;
    }

    /**
     * Get remainingPayment
     *
     * @return float
     */
    public function getRemainingPayment()
    {
        return $this->remainingPayment;
    }

    /**
     * Add payment
     *
     * @param \GS\FestivalBundle\Entity\Payment $payment
     *
     * @return Registration
     */
    public function addPayment(\GS\FestivalBundle\Entity\Payment $payment)
    {
        $this->payments[] = $payment;
        $payment->setRegistration($this);
        $this->remainingPayment -= $payment->getAmount();
        if ($this->remainingPayment <= 0.0) {
            $this->status = 'pre_paid';
        }

        return $this;
    }

    /**
     * Remove payment
     *
     * @param \GS\FestivalBundle\Entity\Payment $payment
     */
    public function removePayment(\GS\FestivalBundle\Entity\Payment $payment)
    {
        $this->payments->removeElement($payment);
    }

    /**
     * Get payments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Registration
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Set assignmentDate
     *
     * @param \DateTime $assignmentDate
     *
     * @return Registration
     */
    public function setAssignmentDate($assignmentDate)
    {
        $this->assignmentDate = $assignmentDate;

        return $this;
    }

    /**
     * Get assignmentDate
     *
     * @return \DateTime
     */
    public function getAssignmentDate()
    {
        return $this->assignmentDate;
    }

    /**
     * Set offerHousing
     *
     * @param boolean $offerHousing
     *
     * @return Registration
     */
    public function setOfferHousing($offerHousing)
    {
        $this->offerHousing = $offerHousing;

        return $this;
    }

    /**
     * Get offerHousing
     *
     * @return boolean
     */
    public function getOfferHousing()
    {
        return $this->offerHousing;
    }

    /**
     * Set doubleBeds
     *
     * @param integer $doubleBeds
     *
     * @return Registration
     */
    public function setDoubleBeds($doubleBeds)
    {
        $this->doubleBeds = $doubleBeds;

        return $this;
    }

    /**
     * Get doubleBeds
     *
     * @return integer
     */
    public function getDoubleBeds()
    {
        return $this->doubleBeds;
    }

    /**
     * Set singleBeds
     *
     * @param integer $singleBeds
     *
     * @return Registration
     */
    public function setSingleBeds($singleBeds)
    {
        $this->singleBeds = $singleBeds;

        return $this;
    }

    /**
     * Get singleBeds
     *
     * @return integer
     */
    public function getSingleBeds()
    {
        return $this->singleBeds;
    }

    /**
     * Set comments
     *
     * @param string $comments
     *
     * @return Registration
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set requestHousing
     *
     * @param boolean $requestHousing
     *
     * @return Registration
     */
    public function setRequestHousing($requestHousing)
    {
        $this->requestHousing = $requestHousing;

        return $this;
    }

    /**
     * Get requestHousing
     *
     * @return boolean
     */
    public function getRequestHousing()
    {
        return $this->requestHousing;
    }

    /**
     * Set shareBed
     *
     * @param string $shareBed
     *
     * @return Registration
     */
    public function setShareBed($shareBed)
    {
        $this->shareBed = $shareBed;

        return $this;
    }

    /**
     * Get shareBed
     *
     * @return string
     */
    public function getShareBed()
    {
        return $this->shareBed;
    }

    /**
     * Set roommates
     *
     * @param string $roommates
     *
     * @return Registration
     */
    public function setRoommates($roommates)
    {
        $this->roommates = $roommates;

        return $this;
    }

    /**
     * Get roommates
     *
     * @return string
     */
    public function getRoommates()
    {
        return $this->roommates;
    }
}
