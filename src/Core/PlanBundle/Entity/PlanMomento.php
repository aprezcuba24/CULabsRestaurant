<?php

namespace Core\PlanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Core\PlanBundle\Entity\PlanMomento
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Core\PlanBundle\Entity\PlanMomentoRepository")
 */
class PlanMomento
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**    
     * @ORM\ManyToOne(targetEntity="Plan", inversedBy="plan_momento")
     * @Assert\NotBlank()
     */    
    private $plan;
    
    /**    
     * @ORM\ManyToOne(targetEntity="Momento", inversedBy="plan_momento")
     * @Assert\NotBlank()
     */    
    private $momento;
    
    /**    
     * @ORM\ManyToMany(targetEntity="Menu", inversedBy="plan_momentos")
     * @Assert\NotBlank()
     */    
    private $menus;

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
     * Constructor
     */
    public function __construct()
    {
        $this->menus = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set plan
     *
     * @param Core\PlanBundle\Entity\Plan $plan
     * @return PlanMomento
     */
    public function setPlan(\Core\PlanBundle\Entity\Plan $plan = null)
    {
        $this->plan = $plan;
    
        return $this;
    }

    /**
     * Get plan
     *
     * @return Core\PlanBundle\Entity\Plan 
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * Set momento
     *
     * @param Core\PlanBundle\Entity\Momento $momento
     * @return PlanMomento
     */
    public function setMomento(\Core\PlanBundle\Entity\Momento $momento = null)
    {
        $this->momento = $momento;
    
        return $this;
    }

    /**
     * Get momento
     *
     * @return Core\PlanBundle\Entity\Momento 
     */
    public function getMomento()
    {
        return $this->momento;
    }

    /**
     * Add menus
     *
     * @param Core\PlanBundle\Entity\Menu $menus
     * @return PlanMomento
     */
    public function addMenu(\Core\PlanBundle\Entity\Menu $menus)
    {
        $this->menus[] = $menus;
    
        return $this;
    }

    /**
     * Remove menus
     *
     * @param Core\PlanBundle\Entity\Menu $menus
     */
    public function removeMenu(\Core\PlanBundle\Entity\Menu $menus)
    {
        $this->menus->removeElement($menus);
    }

    /**
     * Get menus
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMenus()
    {
        return $this->menus;
    }
}