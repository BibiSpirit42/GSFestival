<?php

namespace GS\FestivalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Payment
 *
 * @ORM\Table(name="payment")
 * @ORM\Entity(repositoryClass="GS\FestivalBundle\Repository\PaymentRepository")
 */
class Payment
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
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="GS\FestivalBundle\Entity\Registration", inversedBy="payments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $registration;

    
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
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return Payment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Payment
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set registration
     *
     * @param \GS\FestivalBundle\Entity\Registration $registration
     *
     * @return Payment
     */
    public function setRegistration(\GS\FestivalBundle\Entity\Registration $registration)
    {
        $this->registration = $registration;

        return $this;
    }

    /**
     * Get registration
     *
     * @return \GS\FestivalBundle\Entity\Registration
     */
    public function getRegistration()
    {
        return $this->registration;
    }
}
