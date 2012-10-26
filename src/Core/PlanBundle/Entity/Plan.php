<?php

namespace Core\PlanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Core\PlanBundle\Entity\Plan
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Core\PlanBundle\Entity\PlanRepository")
 * @UniqueEntity("fecha")
 */
class Plan
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
     * @var \DateTime $fecha
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @var int $cantidad_raciones
     *
     * @ORM\Column(name="cantidad_raciones", type="integer")
     */
    private $cantidad_raciones;
    
    /**
     * @ORM\OneToMany(targetEntity="PlanMomento", mappedBy="plan")
     */
    private $plan_momento; 
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->plan_momento = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Plan
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set cantidad_raciones
     *
     * @param integer $cantidadRaciones
     * @return Plan
     */
    public function setCantidadRaciones($cantidadRaciones)
    {
        $this->cantidad_raciones = $cantidadRaciones;
    
        return $this;
    }

    /**
     * Get cantidad_raciones
     *
     * @return integer 
     */
    public function getCantidadRaciones()
    {
        return $this->cantidad_raciones;
    }    
    
    /**
     * Add plan_momento
     *
     * @param Core\PlanBundle\Entity\PlanMomento $planMomento
     * @return Plan
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