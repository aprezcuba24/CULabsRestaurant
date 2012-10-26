<?php

namespace Core\PlanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Core\PlanBundle\Entity\Menu
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Core\PlanBundle\Entity\MenuRepository")
 * @Vich\Uploadable
 */
class Menu
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
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;
    
    /**    
     * @ORM\ManyToMany(targetEntity="Core\PlatoBundle\Entity\Plato", inversedBy="menus")
     * @Assert\NotBlank()
     */    
    private $platos;
    
    /**    
     * @ORM\ManyToOne(targetEntity="CatMenu")
     * @Assert\NotBlank()
     */    
    private $categoria;
    
    /**
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="menu_foto", fileNameProperty="foto")
     *
     * @var File $image
     */
    private $foto_file;
    
    /**
     * @var string $update_foto
     *
     * @ORM\Column(name="update_foto", type="string", length=255)
     */
    private $update_foto;

    /**
     * @var string $foto
     *
     * @ORM\Column(name="foto", type="string", length=255)
     */
    private $foto;
    
    /**
     * @var string $descripcion
     *
     * @ORM\Column(name="descripcion", type="text")
     */
    private $descripcion;
    
    /**
     * @var string $resumen
     *
     * @ORM\Column(name="resumen", type="text")
     */
    private $resumen;
    
    /**
     * @ORM\ManyToMany(targetEntity="PlanMomento", mappedBy="menus")
     */
    private $plan_momentos; 
    
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
    
    public function setFotoFile($foto_file)
    {
        $this->foto_file = $foto_file;
        
        $this->setUpdateFoto(uniqid());
        
        return $this;
    }
    
    public function getFotoFile()
    {
        return $this->foto_file;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Menu
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
     * Constructor
     */
    public function __construct()
    {
        $this->platos = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set foto
     *
     * @param string $foto
     * @return Menu
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;
    
        return $this;
    }

    /**
     * Get foto
     *
     * @return string 
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Add platos
     *
     * @param Core\PlatoBundle\Entity\Plato $platos
     * @return Menu
     */
    public function addPlato(\Core\PlatoBundle\Entity\Plato $platos)
    {
        $this->platos[] = $platos;
    
        return $this;
    }

    /**
     * Remove platos
     *
     * @param Core\PlatoBundle\Entity\Plato $platos
     */
    public function removePlato(\Core\PlatoBundle\Entity\Plato $platos)
    {
        $this->platos->removeElement($platos);
    }

    /**
     * Get platos
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPlatos()
    {
        return $this->platos;
    }

    /**
     * Set categoria
     *
     * @param Core\PlanBundle\Entity\CatMenu $categoria
     * @return Menu
     */
    public function setCategoria(\Core\PlanBundle\Entity\CatMenu $categoria = null)
    {
        $this->categoria = $categoria;
    
        return $this;
    }

    /**
     * Get categoria
     *
     * @return Core\PlanBundle\Entity\CatMenu 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set update_foto
     *
     * @param string $updateFoto
     * @return Menu
     */
    public function setUpdateFoto($updateFoto)
    {
        $this->update_foto = $updateFoto;
    
        return $this;
    }

    /**
     * Get update_foto
     *
     * @return string 
     */
    public function getUpdateFoto()
    {
        return $this->update_foto;
    }

    /**
     * Add plan_momentos
     *
     * @param Core\PlanBundle\Entity\PlanMomento $planMomentos
     * @return Menu
     */
    public function addPlanMomento(\Core\PlanBundle\Entity\PlanMomento $planMomentos)
    {
        $this->plan_momentos[] = $planMomentos;
    
        return $this;
    }

    /**
     * Remove plan_momentos
     *
     * @param Core\PlanBundle\Entity\PlanMomento $planMomentos
     */
    public function removePlanMomento(\Core\PlanBundle\Entity\PlanMomento $planMomentos)
    {
        $this->plan_momentos->removeElement($planMomentos);
    }

    /**
     * Get plan_momentos
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPlanMomentos()
    {
        return $this->plan_momentos;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Menu
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set resumen
     *
     * @param string $resumen
     * @return Menu
     */
    public function setResumen($resumen)
    {
        $this->resumen = $resumen;
    
        return $this;
    }

    /**
     * Get resumen
     *
     * @return string 
     */
    public function getResumen()
    {
        return $this->resumen;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Menu
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
}