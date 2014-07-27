<?php

namespace GNKWLDF\LdfcorpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PageLink
 *
 * @ORM\Table(name="page_link")
 * @ORM\Entity(repositoryClass="GNKWLDF\LdfcorpBundle\Entity\PageLinkRepository")
 */
class PageLink
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;
    
    /**
     * @var GNKWLDF\LdfcorpBundle\Entity\Page
     *
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="links")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    protected $page;


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
     * @return PageLink
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
     * Set title
     *
     * @param string $title
     * @return PageLink
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return PageLink
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    public function setPage($page)
    {
        $this->page = $page;
    }
    
    public function getPage()
    {
        return $this->page;
    }
}
