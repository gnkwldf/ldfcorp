<?php

namespace GNKWLDF\LdfcorpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use GNKWLDF\LdfcorpBundle\Validator\Constraints as GNKWAssert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Page
 *
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="GNKWLDF\LdfcorpBundle\Entity\PageRepository")
 */
class Page
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
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="video_link", type="string", length=255, nullable=true)
     * @GNKWAssert\IframeVideo
     */
    private $videoLink;

    /**
     * @var string
     *
     * @ORM\Column(name="chat_link", type="string", nullable=true)
     * @GNKWAssert\IframeChat
     */
    protected $chatLink;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \GNKWLDF\LdfcorpBundle\Entity\PageLink
     *
     * @ORM\OneToMany(targetEntity="PageLink", mappedBy="page", cascade={"persist", "remove"})
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $links;

    /**
     * @var boolean
     *
     * @ORM\Column(name="online", type="boolean", nullable=false, options={"default" : false})
     */
    private $online;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_online", type="datetime", nullable=true)
     */
    private $lastOnline;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation", type="datetime", nullable=false)
     */
    private $creation;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ads", type="boolean", nullable=false, options={"default" : false})
     */
    private $ads;
    
    /**
     * @var \GNKWLDF\LdfcorpBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="pages")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    public function __construct()
    {
        $this->online = false;
        $this->creation = new DateTime('now');
        $this->lastOnline = new DateTime('now');
        $this->links = new ArrayCollection();
    }
    
    public function setUser($user)
    {
        $this->user = $user;
    }
    
    public function getUser()
    {
        return $this->user;
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
     * @return Page
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
     * Set videoLink
     *
     * @param string $videoLink
     * @return Page
     */
    public function setVideoLink($videoLink)
    {
        $this->videoLink = $videoLink;

        return $this;
    }

    /**
     * Get videoLink
     *
     * @return string
     */
    public function getVideoLink()
    {
        return $this->videoLink;
    }

    /**
     * @return string
     */
    public function getChatLink()
    {
        return $this->chatLink;
    }

    /**
     * @param string $chatLink
     */
    public function setChatLink($chatLink)
    {
        $this->chatLink = $chatLink;
    }
    
    /**
     * Set description
     *
     * @param string $description
     * @return Page
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
     * Set creation
     *
     * @param \DateTime $creation
     * @return Page
     */
    public function setCreation($creation)
    {
        $this->creation = $creation;

        return $this;
    }

    /**
     * Get creation
     *
     * @return \DateTime 
     */
    public function getCreation()
    {
        return $this->creation;
    }

    /**
     * Set online
     *
     * @param boolean $online
     * @return Page
     */
    public function setOnline($online)
    {
        $this->online = $online;
        $this->lastOnline = new DateTime('now');

        return $this;
    }

    /**
     * Get online
     *
     * @return boolean 
     */
    public function getOnline()
    {
        return $this->online;
    }

    /**
     * Set ads
     *
     * @param boolean $ads
     * @return Page
     */
    public function setAds($ads)
    {
        $this->ads = $ads;

        return $this;
    }

    /**
     * Get ads
     *
     * @return boolean 
     */
    public function getAds()
    {
        return $this->ads;
    }

    /**
     * Set lastOnline
     *
     * @param \DateTime $lastOnline
     * @return Page
     */
    public function setLastOnline($lastOnline)
    {
        $this->lastOnline = $lastOnline;

        return $this;
    }

    /**
     * Get lastOnline
     *
     * @return \DateTime 
     */
    public function getLastOnline()
    {
        return $this->lastOnline;
    }

    /**
     * Set links
     *
     * @param array $links
     * @return Page
     */
    public function setLinks($links)
    {
        $this->links = $links;

        return $this;
    }
    
    public function addLink($link)
    {
        $link->setPage($this);
        $this->links->add($link);
    }
    
    public function removeLink($link)
    {
        $this->links->removeElement($link);
    }

    /**
     * Get links
     *
     * @return array 
     */
    public function getLinks()
    {
        return $this->links;
    }
}
