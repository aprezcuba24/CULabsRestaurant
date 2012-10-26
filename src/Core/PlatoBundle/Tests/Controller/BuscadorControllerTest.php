<?php

namespace Core\PlatoBundle\Tests\Controller;

use Core\PlanBundle\TestUtil\TestCase;

class BuscadorControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->createPlato(array(
            'name' => 'plato aa',            
        ));
        $this->createPlato(array(
            'name' => 'plato2 aa',            
        ));
        $this->createPlato(array(
            'name' => 'plato bb',            
        ));
    }
    public function testBuscar()
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $form = $crawler->selectButton('Buscar')->form(array(
            'q' => 'aa'
        ));
        
        $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());        
        $crawler = $client->getCrawler();
        
        $this->assertEquals(2, $crawler->filter('.platos_buscador_list .item')->count(), 'Buscador: Solo hay dos platos con dos "a" en el nombre');
    }
}