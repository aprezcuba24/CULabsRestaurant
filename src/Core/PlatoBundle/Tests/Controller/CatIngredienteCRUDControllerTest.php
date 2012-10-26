<?php

namespace Core\PlatoBundle\Tests\Controller;

use Core\PlanBundle\TestUtil\TestCase;

class CatIngredienteCRUDControllerTest extends TestCase
{
    public function testCompleteScenario()
    {        
        $client = $this->login('admin', 'admin');

        // Create a new entry in the database
        $crawler = $client->request('GET', '/admin/ingrediente/categoria/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // Fill in the form and submit it
        $form = $crawler->selectButton('Salvar')->form(array(
            'core_platobundle_catingredientetype[name]'  => 'Test',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertTrue($crawler->filter('td:contains("Test")')->count() > 0);

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Editar')->link());

        $form = $crawler->selectButton('Salvar')->form(array(
            'core_platobundle_catingredientetype[name]'  => 'Foo',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertTrue($crawler->filter('td:contains("Foo")')->count() > 0);

        // Delete the entity
        $entity = $client->getContainer()->get('doctrine')->getEntityManager()->getRepository('CorePlatoBundle:CatIngrediente')->findOneByName('Foo');
        $crawler = $client->request('GET', sprintf('/admin/ingrediente/categoria/%s/delete', $entity->getId()));
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }
}