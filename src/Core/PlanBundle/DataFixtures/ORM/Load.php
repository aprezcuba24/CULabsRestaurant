<?php

namespace Core\PlanBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Core\PlanBundle\DataFixtures\Util;
use Core\PlanBundle\Entity\CatMenu;
use Core\PlanBundle\Entity\Momento;
use Core\PlanBundle\Entity\Menu;
use Core\PlanBundle\Entity\Plan;
use Core\PlanBundle\Entity\PlanMomento;

class Load extends AbstractFixture implements ContainerAwareInterface
{
    private $container;
    
    function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function load(ObjectManager $manager)
    {
        foreach (array('Normal', 'Niño', 'Diabético', 'Fin de año') as $name) {
            $entity = new CatMenu();
            $entity->setName($name);
            
            $manager->persist($entity);
            $this->addReference('cat_menu_'.$name, $entity);
        }
        
        $momentos = array(
            array('Desayuno', '07:00'),
            array('Almuerzo', '12:00'),
            array('Comida', '19:00'),
        );
        foreach ($momentos as $item) {
            $entity = new Momento();
            $entity->setName($item[0]);
            $entity->setHora(new \DateTime($item[1]));
            
            $this->addReference('momento_'.$item[0], $entity);
            
            $manager->persist($entity);
        }
        $manager->flush();
        
        $filesystem = $this->container->get('filesystem');
        $files_dir = dirname(__FILE__).'/../../../PlanBundle/DataFixtures/Files';
        $web_dir = dirname(__FILE__).'/../../../../../web/uploads/menu';
        
        $images = array('dulce1', 'dulce2', 'dulce3', 'dulce4', 'bebida1', 'bebida2');
        
        for ($i = 0; $i < 6; $i++) {
            
            $filesystem->copy(sprintf('%s/%s.png', $files_dir, $images[$i]), sprintf('%s/%s.png', $web_dir, $images[$i]));
            
            $entity = new Menu();
            $entity->setName('menu '.($i + 1));
            $entity->setCategoria($this->getReference('cat_menu_Normal'));
            $entity->setResumen('Resumen de prueba para el menú menu'.($i + 1).Util::getLoremText(100));
            $entity->setDescripcion('Descripción de prueba para el menú menu'.($i + 1).' '.Util::getLoremText(1000));
            $entity->setUpdateFoto(uniqid());
            $entity->setFoto($images[$i].'.png');
            $entity->addPlato($this->getReference('plato_dulce1'));
            $entity->addPlato($this->getReference('plato_dulce2'));
            $entity->addPlato($this->getReference('plato_bebida1'));
            
            $this->addReference('menu_'.($i + 1), $entity);
            
            $manager->persist($entity);
        }
        $manager->flush();
        
        $alternar = true;        
        $fecha = new \DateTime();
        $fecha->sub(new \DateInterval('P0Y0M35DT0H0M0S'));
        for ($i = 0; $i < 70; $i++) {
            
            $fecha = $fecha->add(new \DateInterval('P0Y0M1DT0H0M0S'));
            $this->createPlan($manager, $alternar, array(
                'fecha' => $fecha,
            ));
            $alternar = !$alternar;
        }    
    }
    private function createPlan($manager, $alternar, $param = array())
    {
        $entity = new Plan();
        $entity->setCantidadRaciones(isset($param['cantidad_raciones'])? $param['cantidad_raciones']: 10);
        $entity->setFecha(isset($param['fecha'])? $param['fecha']: new \DateTime());
        
        $manager->persist($entity);
        $manager->flush();
        
        foreach (array('Desayuno', 'Almuerzo', 'Comida') as $item) {
            
            $plan_momento = new PlanMomento();
            $plan_momento->setMomento($this->getReference('momento_'.$item));
            $plan_momento->setPlan($entity);
            
            if ($alternar) {
                
                $plan_momento->addMenu($this->getReference('menu_4'));
                $plan_momento->addMenu($this->getReference('menu_5'));
                $plan_momento->addMenu($this->getReference('menu_6'));
            } else {
                
                $plan_momento->addMenu($this->getReference('menu_1'));
                $plan_momento->addMenu($this->getReference('menu_2'));
                $plan_momento->addMenu($this->getReference('menu_3'));
            }
            $alternar = !$alternar;
            
            $manager->persist($plan_momento);
            $manager->flush();
        }
        
        return $entity;
    }
}