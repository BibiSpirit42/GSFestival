<?php

namespace GS\FestivalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
    private $status = 'status_received';

    /**
     * @var \DateTime
     *
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

    public function __construct()
    {
        $this->date = new \Datetime();
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

}
