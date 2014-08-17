<?php

namespace GNKWLDF\LdfcorpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Poll
 *
 * @ORM\Table(name="poll")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="GNKWLDF\LdfcorpBundle\Entity\PollRepository")
 */
class Poll
{

    const TIMEOUT_USER = 5;
    const TIMEOUT_ANONYMOUS = 10;
    const LIMIT_USER = 500;
    const LIMIT_ANONYMOUS = 50;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=false, options={"default" : false})
     */
    private $active;
    
    /**
     * @var GNKWLDF\LdfcorpBundle\Entity\VotingEntry
     *
     * @ORM\OneToMany(targetEntity="VotingEntry", mappedBy="poll", cascade={"persist", "remove"})
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $votingEntries;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="datetime", nullable=true)
     */
    private $updateDate;
    
    public function __construct()
    {
        $this->votingEntries = new ArrayCollection();
        $this->active = false;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Poll
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
     * Set active
     *
     * @param boolean $active
     * @return Poll
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }
    
    /**
     * Set votingEntries
     *
     * @param array $votingEntries
     * @return Poll
     */
    public function setVotingEntries($votingEntries)
    {
        $this->votingEntries = $votingEntries;

        return $this;
    }
    
    public function addVotingEntry($votingEntry)
    {
        $votingEntry->setPoll($this);
        $this->votingEntries->add($votingEntry);
    }
    
    public function removeVotingEntry($votingEntry)
    {
        $this->votingEntries->removeElement($votingEntry);
    }

    /**
     * Get votingEntries
     *
     * @return array 
     */
    public function getVotingEntries()
    {
        return $this->votingEntries;
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updateDate = new DateTime('now');
    }
}
