<?php

namespace Core\PlanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Core\PlatoBundle\Entity\Momento
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Core\PlanBundle\Entity\MomentoRepository")
 */
class Momento
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var \DateTime $hora
     *
     * @ORM\Column(name="hora", type="time")
     * @Assert\NotBlank()
     */
    private $hora;
    
    /**    
     * @ORM\OneToMany(targetEntity="PlanMomento", mappedBy="momento")
     */    
    private $plan_momento;

    public function __toString()
    {
        return $this->getName();
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
     * @return Momento
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
     * Set hora
     *
     * @param \DateTime $hora
     * @return Momento
     */
    public function setHora($hora)
    {
        $this->hora = $hora;
    
        return $this;
    }

    /**
     * Get hora
     *
     * @return \DateTime 
     */
    public function getHora()
    {
        return $this->hora;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->plan_momento = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add plan_momento
     *
     * @param Core\PlanBundle\Entity\PlanMomento $planMomento
     * @return Momento
     */
    public function addPlanMomento(\Core\PlanBundle\Entity\PlanMomento $planMomento)
    {
        $this->plan_momento[] = $planMomento;
    
        return $this;
    }

    /**
     * Remove plan_momento
     *
     * @param Core\PlanBundle\Entity\PlanMomento $planMomento
     */
    public function removePlanMomento(\Core\PlanBundle\Entity\PlanMomento $planMomento)
    {
        $this->plan_momento->removeElement($planMomento);
    }

    /**
     * Get plan_momento
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPlanMomento()
    {
        return $this->plan_momento;
    }
}