<?php

namespace Core\PlanBundle\Tests\Controller;

use Core\PlanBundle\TestUtil\TestCase;

class MenuCRUDControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->createPlato(array(
            'name' => 'test_plato',
        ));
        $this->createCatMenu(array(
            'name' => 'test_cat_menu',
        ));
    }
    public function tearDown()
    {
        $this->removePlatoEntity('test_plato');
        $this->removeCatMenuEntity('test_cat_menu');
        parent::tearDown();
    }
    public function testCompleteScenario()
    {
        $client = $this->login('admin', 'admin');

        // Create a new entry in the database
        $crawler = $client->request('GET', '/admin/menu/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // Fill in the form and submit it
        $form = $crawler->selectButton('Salvar')->form(array(
            'core_planbundle_menutype[name]'        => 'Test',
            'core_planbundle_menutype[resumen]'     => 'Test',
            'core_planbundle_menutype[descripcion]' => 'Test',
            'core_planbundle_menutype[foto_file]'   => $this->getImagen(),
            'core_planbundle_menutype[platos]'      => $this->getPlatoEntity('test_plato')->getId(),
            'core_planbundle_menutype[categoria]'   => $this->getCatMenuEntity('test_cat_menu')->getId(),
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertTrue($crawler->filter('td:contains("Test")')->count() > 0);

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Editar')->link());

        $form = $crawler->selectButton('Salvar')->form(array(
            'core_planbundle_menutype[name]'      => 'Foo',
            'core_planbundle_menutype[foto_file]' => $this->getImagen(),
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertTrue($crawler->filter('td:contains("Foo")')->count() > 0);

        // Delete the entity
        $entity = $client->getContainer()->get('doctrine')->getEntityManager()->getRepository('CorePlanBundle:Menu')->findOneByName('Foo');
        $crawler = $client->request('GET', sprintf('/admin/menu/%s/delete', $entity->getId()));
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }    
}