<?php

namespace Core\PlatoBundle\Tests\Controller;

use Core\PlanBundle\TestUtil\TestCase;

class DefaultControllerTest extends TestCase
{
    public function testShow()
    {      
        $menu = $this->createMenu(array(
            'name' => 'menu_test',
        ));
        
        $plato = $this->createPlato(array(
            'name'      => 'plato_test',
            'destacado' => true,
        ));
        
        $menu->addPlato($plato);
        
        for ($i = 1; $i < 4; $i++) {
            
            $plato = $this->createPlato();
            
            $menu->addPlato($plato);
        }
        
        $this->getEm()->persist($menu);
        $this->getEm()->flush();
        
        $plan = $this->createPlan(array(
            'fecha' => new \DateTime(),
        ));
        $momento = $this->createMomento(array(
            'name' => 'momento',
        ));
        $plan_momento = new \Core\PlanBundle\Entity\PlanMomento();
        $plan_momento->setPlan($plan);
        $plan_momento->setMomento($momento);  
        
        $plan_momento->addMenu($menu);
        $this->getEm()->persist($plan_momento);
        $this->getEm()->flush();
        
        
        //Comienzo de las pruebas        
        $client = self::createClient();
        
        //Show
        $crawler = $client->request('GET', sprintf('/menu/%s/plato/%s', $menu->getSlug(), $plato->getSlug()));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(3, $crawler->filter('.show_plato .plato_item')->count());
        
        //Show Simple
        $crawler = $client->request('GET', sprintf('/plato/%s', $plato->getSlug()));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        //Destacados
        $this->assertEquals(1, $crawler->filter('.platos_destacados .item')->count());
        
        //Probar los rss
        $crawler = $client->request('GET', '/platos.rss');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $this->assertEquals(4, $crawler->filter('item')->count());
    }
}
