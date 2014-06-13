<?php

namespace GNKWLDF\LdfcorpBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var GNKWLDF\LdfcorpBundle\Entity\Page
     *
     * @ORM\OneToMany(targetEntity="Page", mappedBy="user", cascade={"persist", "remove"})
     * @ORM\OrderBy({"creation" = "DESC"})
     */
    protected $pages;

    public function __construct()
    {
        parent::__construct();
        $this->pages = new ArrayCollection();
    }
    
    public function addPage($page)
    {
        $page->setUser($this);
        $this->pages[] = $page;
    }
    
    public function getPages()
    {
        return $this->pages;
    }
}