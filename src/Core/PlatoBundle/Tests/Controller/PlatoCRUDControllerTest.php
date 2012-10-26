<?php

namespace Core\PlatoBundle\Tests\Controller;

use Core\PlanBundle\TestUtil\TestCase;

class PlatoCRUDControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->createIngrediente(array(
            'name' => 'test_ingrediente',
        ));
    }
    public function tearDown()
    {
        $this->removeIngredienteEntity('test_ingrediente');
        parent::tearDown();
    }
    public function testCompleteScenario()
    {
        $client = $this->login('admin', 'admin');

        // Create a new entry in the database
        $crawler = $client->request('GET', '/admin/plato/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // Fill in the form and submit it
        $form = $crawler->selectButton('Salvar')->form(array(
            'core_platobundle_platotype[name]'                               => 'Test',
            'core_platobundle_platotype[destacado]'                          => true,
            'core_platobundle_platotype[forma_elaboracion]'                  => 'forma_elaboracion Test',
            'core_platobundle_platotype[datos_nutricionales]'                => 'forma_elaboracion Test',
            'core_platobundle_platotype[descripcion]'                        => 'descripcion Test',
            'core_platobundle_platotype[resumen]'                            => 'resumen Test',
            'core_platobundle_platotype[foto_file]'                          => $this->getImagen(),            
            'core_platobundle_platotype[plato_ingredientes][0][ingrediente]' => $this->getIngredienteEntity('test_ingrediente')->getId(),
            'core_platobundle_platotype[plato_ingredientes][0][cantidad]'    => 2,
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertTrue($crawler->filter('td:contains("Test")')->count() > 0);

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Editar')->link());

        $form = $crawler->selectButton('Salvar')->form(array(
            'core_platobundle_platotype[name]'      => 'Foo',
            'core_platobundle_platotype[foto_file]' => $this->getImagen(),
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertTrue($crawler->filter('td:contains("Foo")')->count() > 0);

        // Delete the entity
        $entity = $client->getContainer()->get('doctrine')->getEntityManager()->getRepository('CorePlatoBundle:Plato')->findOneByName('Foo');
        $crawler = $client->request('GET', sprintf('/admin/plato/%s/delete', $entity->getId()));
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }
}