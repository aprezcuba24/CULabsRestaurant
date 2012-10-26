<?php

namespace Core\PlanBundle\TestUtil;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class TestCase extends WebTestCase
{
    private $em;
    
    public function setUp()
    {
        $purger = new ORMPurger($this->getEm());
        $purger->purge();
        
        $this->createUser();
    }    
    protected function login($user, $pass, $client = null, $login_path = '/admin/login')
    {
        if (!$client) {
            
            $client = static::createClient();
        }
        
        $crawler = $client->request('GET', $login_path);
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Entrar')->form(array(
            '_username' => $user,
            '_password' => $pass,
        ));
        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        
        return $client;
    }
    protected function getEm()
    {
        if (!$this->em) {
            
            $client = static::createClient();
            $this->em = $client->getContainer()->get('doctrine')->getEntityManager();
        }
        return $this->em;
    }
    protected function getImagen()
    {
        return new UploadedFile(
            __DIR__.'/../Tests/files/imagen.jpg',
            'imagen.jpg'
        );
    }
    
    //User
    protected function createUser($param = array())
    {
        $entity = new \Core\UserBundle\Entity\User();
        $entity->setUsername(isset($param['username'])? $param['username']: 'admin');
        $entity->setPlainPassword(isset($param['plain_password'])? $param['username']: 'admin');
        $entity->setRoles(isset($param['roles'])? $param['roles']: array('ROLE_SUPER_ADMIN'));
        $entity->setEmail(isset($param['email'])? $param['email']: 'admin@culabsrestaurant.localhost');
        $entity->setEnabled(true);
        
        $this->getEm()->persist($entity);
        $this->getEm()->flush();
        
        return $entity;
    }
    //Fin User
    
    //Categoria Ingrediente
    protected function createCatIngrediente($param = array())
    {
        $entity = new \Core\PlatoBundle\Entity\CatIngrediente();
        $entity->setName(isset($param['name'])? $param['name']: uniqid('test_cat_ingrediente'));
        
        $this->getEm()->persist($entity);
        $this->getEm()->flush();
        
        return $entity;
    }
    public function removeCatIngredienteEntity($name)
    {
        $this->getEm()->remove($this->getCatIngredienteEntity($name));
        $this->getEm()->flush();
    }
    protected function getCatIngredienteEntity($name)
    {
        return $this->getEm()->getRepository('CorePlatoBundle:CatIngrediente')->findOneByName($name);
    }
    //Fin Categoria Ingrediente
    
    //Ingrediente
    protected function createIngrediente($param = array())
    {
        $entity = new \Core\PlatoBundle\Entity\Ingrediente();        
        $entity->setName(isset($param['name'])? $param['name']: uniqid('test_ingrediente'));
        $entity->setPesoUnitario(isset($param['peso_unitario'])? $param['peso_unitario']: 10);
        $entity->setCategoria($this->createCatIngrediente());
        
        $this->getEm()->persist($entity);
        $this->getEm()->flush();
        
        return $entity;
    }
    public function removeIngredienteEntity($name)
    {
        $entity = $this->getIngredienteEntity($name);
        
        $categoria = $entity->getCategoria();
        
        $this->getEm()->remove($entity);
        $this->getEm()->flush();
        
        $this->removeCatIngredienteEntity($categoria->getName());
    }
    protected function getIngredienteEntity($name)
    {
        return $this->getEm()->getRepository('CorePlatoBundle:Ingrediente')->findOneByName($name);
    }
    //Fin Ingrediente
    
    //Plato
    protected function createPlato($param = array())
    {
        $entity = new \Core\PlatoBundle\Entity\Plato();
        
        $entity->setName(isset($param['name'])? $param['name']: uniqid('test_plato'));
        $entity->setFormaElaboracion(isset($param['forma_elaboracion'])? $param['forma_elaboracion']: uniqid('forma_elaboracion'));
        $entity->setDatosNutricionales(isset($param['datos_nutricionales'])? $param['datos_nutricionales']: uniqid('datos_nutricionales'));                
        $entity->setFoto(uniqid());
        $entity->setUpdateFoto(uniqid());
        $entity->setDescripcion('descripcion test');
        $entity->setResumen('resumen test');
        $entity->setDestacado(isset($param['destacado'])? $param['destacado']: false);
        
        $poner_ingrediente = isset($param['poner_ingrediente'])? $param['poner_ingrediente']: true;
        if ($poner_ingrediente) {
            
            $plato_ingrediente = new \Core\PlatoBundle\Entity\PlatoIngrediente();
            $plato_ingrediente->setCantidad(10);
            $plato_ingrediente->setIngrediente($this->createIngrediente());
            $entity->addPlatoIngrediente($plato_ingrediente);
        }
        
        $this->getEm()->persist($entity);
        $this->getEm()->flush();
        
        return $entity;
    }
    protected function getPlatoEntity($name)
    {
        return $this->getEm()->getRepository('CorePlatoBundle:Plato')->findOneByName($name);
    }
    protected function removePlatoEntity($name)
    {
        $entity = $this->getPlatoEntity($name);
        
        foreach ($entity->getPlatoIngredientes() as $item) {
            
            $this->getEm()->remove($item);
        }
        
        $entity->setFoto(null);
        
        $this->getEm()->remove($entity);
        
        $this->getEm()->flush();
    }
    //Fin Plato
    
    //Categoria Menu
    protected function createCatMenu($param = array())
    {
        $entity = new \Core\PlanBundle\Entity\CatMenu();
        $entity->setName(isset($param['name'])? $param['name']: uniqid('test_cat_menu'));
        
        $this->getEm()->persist($entity);
        $this->getEm()->flush();
        
        return $entity;
    }
    protected function getCatMenuEntity($name)
    {
        return $this->getEm()->getRepository('CorePlanBundle:CatMenu')->findOneByName($name);
    }
    protected function removeCatMenuEntity($name)
    {        
        $this->getEm()->remove($this->getCatMenuEntity($name));
        
        $this->getEm()->flush();
    }
    //Fin Categoria Menu
    
    //Menu
    protected function createMenu($param = array())
    {
        $entity = new \Core\PlanBundle\Entity\Menu();
        $entity->setName(isset($param['name'])? $param['name']: 'name_'.uniqid());
        $entity->setDescripcion(isset($param['descripcion'])? $param['descripcion']: 'descripcion_'.uniqid());
        $entity->setCategoria($this->createCatMenu());
        $entity->setFoto(uniqid());
        $entity->setResumen(isset($param['resumen'])? $param['resumen']: 'resumen_'.uniqid());
        $entity->setUpdateFoto(uniqid());
        
        $this->getEm()->persist($entity);
        $this->getEm()->flush();
        
        return $entity;
    }
    protected function getMenuEntity($name)
    {
        return $this->getEm()->getRepository('CorePlanBundle:Menu')->findOneByName($name);
    }
    protected function removeMenuEntity($name)
    {        
        $this->getEm()->remove($this->getMenuEntity($name));
        
        $this->getEm()->flush();
    }
    //Fin Menu
    
    //Plan
    protected function createPlan($param = array())
    {
        $entity = new \Core\PlanBundle\Entity\Plan();
        $entity->setCantidadRaciones(isset($param['cantidad_raciones'])? $param['cantidad_raciones']: 10);
        $entity->setFecha(isset($param['fecha'])? $param['fecha']: new \DateTime());
        
        $this->getEm()->persist($entity);
        $this->getEm()->flush();
        
        return $entity;
    }
    protected function getPlanEntity($fecha)
    {
        return $this->getEm()->getRepository('CorePlanBundle:Plan')->findOneByFecha($fecha);
    }
    protected function removePlanEntity($fecha)
    {        
        $this->getEm()->remove($this->getPlanEntity($fecha));
        
        $this->getEm()->flush();
    }
    //Fin Plan
    
    //Momento
    protected function createMomento($param = array())
    {
        $entity = new \Core\PlanBundle\Entity\Momento();
        $entity->setHora(isset($param['hora'])? $param['hora']: new \DateTime('07:00'));
        $entity->setName(isset($param['name'])? $param['name']: uniqid());
        
        $this->getEm()->persist($entity);
        $this->getEm()->flush();
        
        return $entity;
    }
    protected function getMomentoEntity($name)
    {
        return $this->getEm()->getRepository('CorePlanBundle:Momento')->findOneByName($name);
    }
    protected function removeMomentoEntity($name)
    {        
        $this->getEm()->remove($this->getMomentoEntity($name));
        
        $this->getEm()->flush();
    }
    //Fin Momento
}