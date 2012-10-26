<?php

namespace Core\PlanBundle\Tests\Controller;

use Core\PlanBundle\TestUtil\TestCase;

class PlanCRUDControllerTest extends TestCase
{
    public function testCompleteScenario()
    {
        $client = $this->login('admin', 'admin');

        // Create a new entry in the database
        $crawler = $client->request('GET', '/admin/plan/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // Fill in the form and submit it
        $form = $crawler->selectButton('Salvar')->form(array(
            'core_planbundle_plantype[cantidad_raciones]'  => 1000000,
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertTrue($crawler->filter('td:contains("1000000")')->count() > 0);

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Editar')->link());

        $form = $crawler->selectButton('Salvar')->form(array(
            'core_planbundle_plantype[cantidad_raciones]'  => 2000000,
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertTrue($crawler->filter('td:contains("2000000")')->count() > 0);

        // Delete the entity
        $entity = $client->getContainer()->get('doctrine')->getEntityManager()->getRepository('CorePlanBundle:Plan')->findOneBy(array(
            'cantidad_raciones' => 2000000,
        ));
        $crawler = $client->request('GET', sprintf('/admin/plan/%s/delete', $entity->getId()));
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/2000000/', $client->getResponse()->getContent());
    }
    private function addPlatoIngrediente($plato, $ingrediente, $cantidad)
    {
        $plato_ingrediente = new \Core\PlatoBundle\Entity\PlatoIngrediente();
        $plato_ingrediente->setCantidad($cantidad);
        $plato_ingrediente->setIngrediente($ingrediente);
        $plato->addPlatoIngrediente($plato_ingrediente);
        
        $this->getEm()->persist($plato);
        $this->getEm()->flush();
    }
    public function testListaCompra()
    {
        $ingrediente1 = $this->createIngrediente(array(
            'peso_unitario' => 10,
        ));
        $ingrediente2 = $this->createIngrediente(array(
            'peso_unitario' => 10,
        ));
        
        $plato1 = $this->createPlato(array(
            'poner_ingrediente' => false,
        ));
        $this->addPlatoIngrediente($plato1, $ingrediente1, 2);
        $this->addPlatoIngrediente($plato1, $ingrediente2, 2);
        
        $plato2 = $this->createPlato(array(
            'poner_ingrediente' => false,
        ));
        $this->addPlatoIngrediente($plato2, $ingrediente1, 3);
        
        $menu = $this->createMenu(array(
            'menu',
        ));
        $menu->addPlato($plato1);
        $menu->addPlato($plato2);        
        
        $plan = $this->createPlan(array(
            'cantidad_raciones' => 2,
        ));
        
        $momento = $this->createMomento(array(
            'name' => 'momento',
        ));
        
        $plan_momento = new \Core\PlanBundle\Entity\PlanMomento();
        $plan_momento->setPlan($plan);
        $plan_momento->setMomento($momento);
        $this->getEm()->persist($plan_momento);
        $this->getEm()->flush();

        $plan_momento->addMenu($menu);
        
        $this->getEm()->persist($plan_momento);
        $this->getEm()->flush();
        
        
        $client = $this->login('admin', 'admin');
        
        $crawler = $client->request('GET', sprintf('/admin/plan/%s/lista-compra', $plan->getId()));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $this->assertEquals(1, $crawler->filter(sprintf('.test-%s-peso-unitario:contains("10")', $ingrediente1->getId()))->count(), 'El ingrediente 1 tiene peso unitario 10');
        $this->assertEquals(1, $crawler->filter(sprintf('.test-%s-peso-unitario:contains("10")', $ingrediente2->getId()))->count(), 'El ingrediente 2 tiene peso unitario 10');
        
        $this->assertEquals(1, $crawler->filter(sprintf('.test-%s-peso-total:contains("100")', $ingrediente1->getId()))->count(), 'El ingrediente 1 tiene peso total 100');
        $this->assertEquals(1, $crawler->filter(sprintf('.test-%s-peso-total:contains("40")', $ingrediente2->getId()))->count(), 'El ingrediente 2 tiene peso total 60');
    }
}