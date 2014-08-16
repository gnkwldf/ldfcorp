<?php

namespace GNKWLDF\LdfcorpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VotingEntry
 *
 * @ORM\Table(name="voting_entry")
 * @ORM\Entity(repositoryClass="GNKWLDF\LdfcorpBundle\Entity\VotingEntryRepository")
 */
class VotingEntry
{
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="vote", type="integer", length=255, nullable=false, options={"default" : 0})
     */
    private $vote;
    
    /**
     * @var GNKWLDF\LdfcorpBundle\Entity\Poll
     *
     * @ORM\ManyToOne(targetEntity="Poll", inversedBy="votingEntries")
     * @ORM\JoinColumn(name="poll_id", referencedColumnName="id")
     */
    protected $poll;
    
    public function __construct()
    {
        $this->vote = false;
    }
    
    /**
     * Increment vote number
     *
     * @return Poll
     */
    public function incrementVote()
    {
        $this->vote++;
        return $this;
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
     * @return VotingEntry
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
     * Set vote
     *
     * @param string $vote
     * @return VotingEntry
     */
    public function setVote($vote)
    {
        $this->vote = $vote;

        return $this;
    }

    /**
     * Get vote
     *
     * @return string 
     */
    public function getVote()
    {
        return $this->vote;
    }
    
    public function setPoll($poll)
    {
        $this->poll = $poll;
    }
    
    public function getPoll()
    {
        return $this->poll;
    }
}
