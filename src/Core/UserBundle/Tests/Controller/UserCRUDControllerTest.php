<?php

namespace Core\UserBundle\Tests\Controller;

use Core\PlanBundle\TestUtil\TestCase;

class UserCRUDControllerTest extends TestCase
{
    public function testCompleteScenario()
    {
        $client = $this->login('admin', 'admin');

        // Create a new entry in the database
        $crawler = $client->request('GET', '/admin/user/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // Fill in the form and submit it
        $form = $crawler->selectButton('Salvar')->form(array(
            'core_userbundle_usertype[username]'       => 'test',
            'core_userbundle_usertype[email]'          => 'test@aa.cu',
            'core_userbundle_usertype[enabled]'        => true,
            'core_userbundle_usertype[plain_password]' => 'test',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertTrue($crawler->filter('td:contains("test")')->count() > 0);

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Editar')->link());

        $form = $crawler->selectButton('Salvar')->form(array(
            'core_userbundle_usertype[username]'       => 'foousername',
            'core_userbundle_usertype[email]'          => 'test@aa.cu',
            'core_userbundle_usertype[enabled]'        => true,
            'core_userbundle_usertype[plain_password]' => 'foousername',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertTrue($crawler->filter('td:contains("foo")')->count() > 0);
        
        //Probar autenticar.
        $client = $this->login('foousername', 'foousername');
        $crawler = $client->request('GET', '/admin/dashboard');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $client = $this->login('admin', 'admin');        

        // Delete the entity
        $entity = $client->getContainer()->get('doctrine')->getEntityManager()->getRepository('CoreUserBundle:User')->findOneByUsername('foousername');
        $crawler = $client->request('GET', sprintf('/admin/user/%s/delete', $entity->getId()));
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/foousername/', $client->getResponse()->getContent());
    }
}