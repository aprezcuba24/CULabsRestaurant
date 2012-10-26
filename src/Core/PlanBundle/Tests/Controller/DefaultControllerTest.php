<?php

namespace Core\PlanBundle\Tests\Controller;

use Core\PlanBundle\TestUtil\TestCase;

class DefaultControllerTest extends TestCase
{
    public function testShow()
    {
        $plan = $this->createPlan(array(
            'fecha' => new \DateTime(),
        ));
        $desayuno = $this->createMomento(array(
            'name' => 'desayuno',
        ));
        $almuerzo = $this->createMomento(array(
            'name' => 'almuerzo',
        ));
        
        $plan_momento = new \Core\PlanBundle\Entity\PlanMomento();
        $plan_momento->setPlan($plan);
        $plan_momento->setMomento($desayuno);        
        $this->getEm()->persist($plan_momento);
        $this->getEm()->flush();
        
        for ($i = 1; $i < 6; $i++) {            
            
            $menu = $this->createMenu(array(
                'menu_'.$i,
            ));
            
            $plan_momento->addMenu($menu);
        }
        
        $plan_momento = new \Core\PlanBundle\Entity\PlanMomento();
        $plan_momento->setPlan($plan);
        $plan_momento->setMomento($almuerzo);
        $this->getEm()->persist($plan_momento);
        $this->getEm()->flush();
        
        for ($i = 6; $i < 11; $i++) {            
            
            $menu = $this->createMenu(array(
                'menu_'.$i,
            ));
            
            $plan_momento->addMenu($menu);
        }
        $this->getEm()->persist($plan_momento);
        $this->getEm()->flush();
        
        $client = self::createClient();
        
        $hoy = new \DateTime();
        
        $crawler = $client->request('GET', '/ofertas/'.$hoy->format('Y-m-d'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(2, $crawler->filter('.momento_titulo')->count());
        $this->assertEquals(10, $crawler->filter('.menu_item')->count());
    }
}
