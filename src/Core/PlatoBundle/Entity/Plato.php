<?php

namespace Core\PlatoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Core\PlatoBundle\Entity\Plato
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Core\PlatoBundle\Entity\PlatoRepository")
 * @Vich\Uploadable
 */
class Plato
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
     * @var boolean $destacado
     *
     * @ORM\Column(name="destacado", type="boolean", nullable=true)
     */
    private $destacado;

    /**
     * @var string $forma_elaboracion
     *
     * @ORM\Column(name="forma_elaboracion", type="text")
     */
    private $forma_elaboracion;

    /**
     * @var string $datos_nutricionales
     *
     * @ORM\Column(name="datos_nutricionales", type="text")
     */
    private $datos_nutricionales;
    
    /**
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="plato_foto", fileNameProperty="foto")
     *
     * @var File $image
     */
    protected $foto_file;

    /**
     * @var string $foto
     *
     * @ORM\Column(name="foto", type="string", length=255)
     */
    private $foto;
    
    /**
     * @var string $update_foto
     *
     * @ORM\Column(name="update_foto", type="string", length=255)
     */
    private $update_foto;
    
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
     * @ORM\OneToMany(targetEntity="PlatoIngrediente", mappedBy="plato", cascade={"persist", "remove"})
     */
    private $plato_ingredientes;
    
    /**    
     * @ORM\ManyToMany(targetEntity="Core\PlanBundle\Entity\Menu", mappedBy="platos")
     */    
    private $menus;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->plato_ingredientes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * @return CatIngrediente
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
     * Set forma_elaboracion
     *
     * @param string $formaElaboracion
     * @return Plato
     */
    public function setFormaElaboracion($formaElaboracion)
    {
        $this->forma_elaboracion = $formaElaboracion;
    
        return $this;
    }

    /**
     * Get forma_elaboracion
     *
     * @return string 
     */
    public function getFormaElaboracion()
    {
        return $this->forma_elaboracion;
    }

    /**
     * Set datos_nutricionales
     *
     * @param string $datosNutricionales
     * @return Plato
     */
    public function setDatosNutricionales($datosNutricionales)
    {
        $this->datos_nutricionales = $datosNutricionales;
    
        return $this;
    }

    /**
     * Get datos_nutricionales
     *
     * @return string 
     */
    public function getDatosNutricionales()
    {
        return $this->datos_nutricionales;
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
     * Set foto
     *
     * @param string $foto
     * @return Plato
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
     * Set update_foto
     *
     * @param string $updateFoto
     * @return Plato
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Plato
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
     * @return Plato
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
     * @return Plato
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

    /**
     * Set destacado
     *
     * @param boolean $destacado
     * @return Plato
     */
    public function setDestacado($destacado)
    {
        $this->destacado = $destacado;
    
        return $this;
    }

    /**
     * Get destacado
     *
     * @return boolean 
     */
    public function getDestacado()
    {
        return $this->destacado;
    }
    
    /**
     * Add plato_ingredientes
     *
     * @param Core\PlatoBundle\Entity\PlatoIngrediente $platoIngredientes
     * @return Plato
     */
    public function addPlatoIngrediente(\Core\PlatoBundle\Entity\PlatoIngrediente $platoIngredientes)
    {
        $platoIngredientes->setPlato($this);
        
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

    /**
     * Add menus
     *
     * @param Core\PlanBundle\Entity\Menu $menus
     * @return Plato
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