<?php

namespace Core\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('enabled', null, array(
                'required' => false,
            ))
            ->add('plain_password')
            ->add('locked', null, array(
                'required' => false,
            ))
        ;
    }

    public function getName()
    {
        return 'core_userbundle_usertype';
    }
}
