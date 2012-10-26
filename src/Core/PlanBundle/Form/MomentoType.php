<?php

namespace Core\PlanBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MomentoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('hora')
        ;
    }

    public function getName()
    {
        return 'core_planbundle_momentotype';
    }
}
