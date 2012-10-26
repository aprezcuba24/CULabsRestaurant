<?php

namespace Core\PlatoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Core\PlatoBundle\Entity\PlatoIngrediente
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Core\PlatoBundle\Entity\PlatoIngredienteRepository")
 */
class PlatoIngrediente
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
     * @var float $cantidad
     *
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad;

    /**    
     * @ORM\ManyToOne(targetEntity="Plato", inversedBy="plato_ingredientes")
     * @Assert\NotBlank()
     */    
    private $plato;
    
    /**    
     * @ORM\ManyToOne(targetEntity="Ingrediente", inversedBy="plato_ingredientes")
     * @Assert\NotBlank()
     */    
    private $ingrediente;

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
     * Set cantidad
     *
     * @param float $cantidad
     * @return PlatoIngrediente
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    
        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set plato
     *
     * @param Core\PlatoBundle\Entity\Plato $plato
     * @return PlatoIngrediente
     */
    public function setPlato(\Core\PlatoBundle\Entity\Plato $plato = null)
    {
        $this->plato = $plato;
    
        return $this;
    }

    /**
     * Get plato
     *
     * @return Core\PlatoBundle\Entity\Plato 
     */
    public function getPlato()
    {
        return $this->plato;
    }

    /**
     * Set ingrediente
     *
     * @param Core\PlatoBundle\Entity\Ingrediente $ingrediente
     * @return PlatoIngrediente
     */
    public function setIngrediente(\Core\PlatoBundle\Entity\Ingrediente $ingrediente = null)
    {
        $this->ingrediente = $ingrediente;
    
        return $this;
    }

    /**
     * Get ingrediente
     *
     * @return Core\PlatoBundle\Entity\Ingrediente 
     */
    public function getIngrediente()
    {
        return $this->ingrediente;
    }
}