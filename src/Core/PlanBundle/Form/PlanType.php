<?php

namespace Core\PlanBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PlanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha')
            ->add('cantidad_raciones')
        ;
    }

    public function getName()
    {
        return 'core_planbundle_plantype';
    }
}
