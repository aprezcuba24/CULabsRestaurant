<?php

namespace Core\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Core\UserBundle\Entity\User;

class Load extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setPlainPassword('admin');
        $user->setRoles(array('ROLE_SUPER_ADMIN'));
        $user->setEmail('admin@culabsrestaurant.localhost');
        $user->setEnabled(true);
        $manager->persist($user); 
        $this->addReference('admin', $user);
        
        $user = new User();
        $user->setUsername('administrador');
        $user->setPlainPassword('administrador');
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setEmail('administrador@culabsrestaurant.localhost');
        $user->setEnabled(true);
        $manager->persist($user); 
        $this->addReference('administrador', $user);
        
        $manager->flush();
    }
}