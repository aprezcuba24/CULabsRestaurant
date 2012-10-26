<?php

namespace Core\PlatoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Core\PlatoBundle\Entity\Ingrediente
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Core\PlatoBundle\Entity\IngredienteRepository")
 */
class Ingrediente
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
     * @var integer $peso_unitario
     *
     * @ORM\Column(name="peso_unitario", type="integer")
     * @Assert\NotBlank()
     */
    private $peso_unitario;
    
    /**    
     * @ORM\ManyToOne(targetEntity="CatIngrediente")
     * @Assert\NotBlank()
     */    
    public $categoria;
    
    /**
     * @ORM\OneToMany(targetEntity="PlatoIngrediente", mappedBy="ingrediente")
     */
    private $plato_ingredientes;
    
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
     * @return Ingrediente
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
     * Set peso_unitario
     *
     * @param integer $pesoUnitario
     * @return Ingrediente
     */
    public function setPesoUnitario($pesoUnitario)
    {
        $this->peso_unitario = $pesoUnitario;
    
        return $this;
    }

    /**
     * Get peso_unitario
     *
     * @return integer 
     */
    public function getPesoUnitario()
    {
        return $this->peso_unitario;
    }

    /**
     * Set categoria
     *
     * @param Core\PlatoBundle\Entity\CatIngrediente $categoria
     * @return Ingrediente
     */
    public function setCategoria(\Core\PlatoBundle\Entity\CatIngrediente $categoria = null)
    {
        $this->categoria = $categoria;
    
        return $this;
    }

    /**
     * Get categoria
     *
     * @return Core\PlatoBundle\Entity\CatIngrediente 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->plato_ingredientes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add plato_ingredientes
     *
     * @param Core\PlatoBundle\Entity\PlatoIngrediente $platoIngredientes
     * @return Ingrediente
     */
    public function addPlatoIngrediente(\Core\PlatoBundle\Entity\PlatoIngrediente $platoIngredientes)
    {
        $this->plato_ingredientes[] = $platoIngredientes;
    
        return $this;
    }

    /**
     * Remove plato_ingredientes
     *
     * @param Core\PlatoBundle\Entity\PlatoIngrediente $platoIngredientes
     */
    public function removePlatoIngrediente(\Core\PlatoBundle\Entity\PlatoIngrediente $platoIngredientes)
    {
        $this->plato_ingredientes->removeElement($platoIngredientes);
    }

    /**
     * Get plato_ingredientes
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPlatoIngredientes()
    {
        return $this->plato_ingredientes;
    }
}