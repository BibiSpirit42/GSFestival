<?php

namespace GS\FestivalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\ManyToOne(targetEntity="GS\FestivalBundle\Entity\Person", inversedBy="registrations", cascade={"persist"})
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
    private $remainingPayment = 0.0;

    /**
     * @ORM\OneToMany(targetEntity="GS\FestivalBundle\Entity\Payment", mappedBy="registration", cascade={"persist", "remove"})
     */
    private $payments;

    /**
     * @ORM\ManyToOne(targetEntity="GS\FestivalBundle\Entity\Registration")
     */
    private $partner;

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
     * @param \GS\FestivalBundle\Entity\Person $person
     *
     * @return Registration
     */
    public function setPerson(\GS\FestivalBundle\Entity\Person $person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \GS\FestivalBundle\Entity\Person
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
            $this->status = 'paid';
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
}
