<?php

namespace GNKWLDF\LdfcorpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pokemon
 *
 * @ORM\Table(name="pokemon")
 * @ORM\Entity(repositoryClass="GNKWLDF\LdfcorpBundle\Entity\PokemonRepository")
 */
class Pokemon
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
     * @var integer
     *
     * @ORM\Column(name="number", type="integer", unique=true, nullable=false)
     */
    private $number;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", options={"default" : false})
     */
    private $active;

    /**
     * @var integer
     *
     * @ORM\Column(name="vote", type="integer", options={"default" : 0})
     */
    private $vote;
    
    /**
     * Pokemon Constructor
     */
    public function __construct()
    {
        $this->active = false;
        $this->vote = 0;
    }
    
    /**
     * Increment vote number
     *
     * @return Pokemon
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
     * Set number
     *
     * @param integer $number
     * @return Pokemon
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Pokemon
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
     * Set vote
     *
     * @param integer $vote
     * @return Pokemon
     */
    public function setVote($vote)
    {
        $this->vote = $vote;

        return $this;
    }

    /**
     * Get vote
     *
     * @return integer 
     */
    public function getVote()
    {
        return $this->vote;
    }
}
