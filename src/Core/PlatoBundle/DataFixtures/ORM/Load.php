<?php

namespace Core\PlatoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Core\PlanBundle\DataFixtures\Util;
use Doctrine\Common\Persistence\ObjectManager;
use Core\PlatoBundle\Entity\CatIngrediente;
use Core\PlatoBundle\Entity\Ingrediente;
use Core\PlatoBundle\Entity\Plato;

class Load extends AbstractFixture implements ContainerAwareInterface
{
    private $container;
    
    function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    public function load(ObjectManager $manager)
    {   
        foreach (array('Condimento', 'Cerial', 'Grasa', 'Carne') as $name) {
            $entity = new CatIngrediente();
            $entity->setName($name);
            $manager->persist($entity);
            $this->addReference('cat_ingrediente_'.$name, $entity);
        }
        $manager->flush();
        
        $data = array(
            array('Vinagre', 'Condimento', 5),
            array('Cebolla', 'Condimento', 10),
            array('Arroz', 'Cerial', 6),
            array('Frijol', 'Cerial', 2),
            array('Cerdo', 'Carne', 8),
            array('Pollo', 'Carne', 6),
        );
        foreach ($data as $item) {
            $entity = new Ingrediente();
            $entity->setName($item[0]);
            $entity->setCategoria($this->getReference('cat_ingrediente_'.$item[1]));
            $entity->setPesoUnitario($item[2]);
            $manager->persist($entity);
            
            $this->addReference('ingrediente_'.$item[0], $entity);
        }
        $manager->flush();
        
        $filesystem = $this->container->get('filesystem');
        $files_dir = dirname(__FILE__).'/../../../PlanBundle/DataFixtures/Files';
        $web_dir = dirname(__FILE__).'/../../../../../web/uploads/platos';
        
        $alternar = true;
        
        foreach (array('dulce1', 'dulce2', 'dulce3', 'dulce4', 'bebida1', 'bebida2') as $item) {
            
            $entity = new Plato();
            $entity->setDatosNutricionales('Datos nutricionales '.Util::getLoremText(300));
            $entity->setFormaElaboracion('Forma de elaboración. '.Util::getLoremText(500));
            $entity->setDescripcion('Descrición de prueba para el plato. '.Util::getLoremText(1000));
            $entity->setResumen('Resumen de prueba'.Util::getLoremText(100));
            $entity->setName('Prueba '.$item);
            $entity->setUpdateFoto(uniqid());
            
            if ($alternar) {
                $entity->setDestacado(true);
            }
            $alternar = !$alternar;

            $filesystem->copy(sprintf('%s/%s.png', $files_dir, $item), sprintf('%s/%s.png', $web_dir, $item));

            $entity->setFoto($item.'.png');
            
            
            foreach (array($this->getReference('ingrediente_Vinagre'), $this->getReference('ingrediente_Cebolla')) as $ingrediente){
                
                $plato_ingrediente = new \Core\PlatoBundle\Entity\PlatoIngrediente();
                $plato_ingrediente->setCantidad(10);
                $plato_ingrediente->setIngrediente($ingrediente);               
                $plato_ingrediente->setPlato($entity);
                
                $manager->persist($plato_ingrediente);
            }
            
            $manager->persist($entity);
            
            $this->addReference('plato_'.$item, $entity);
        }
        $manager->flush();
    }
}