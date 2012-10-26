<?php

namespace Core\PlanBundle\Tests\Controller;

use Core\PlanBundle\TestUtil\TestCase;

class MenuControllerTest extends TestCase
{
    public function testShow()
    {
        $menu = $this->createMenu(array(
            'menu test',
        ));
        
        for ($i = 1; $i < 4; $i++) {
            
            $plato = $this->createPlato();
            
            $menu->addPlato($plato);
        }
        $this->getEm()->persist($menu);
        $this->getEm()->flush();
        
        $client = self::createClient();
        
        $crawler = $client->request('GET', '/menu/'.$menu->getSlug());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(3, $crawler->filter('.plato_item')->count());
    }
}