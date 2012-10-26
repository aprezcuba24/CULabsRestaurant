<?php

namespace Core\PlanBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PlanFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha', 'filter_date')
        ;
    }

    public function getName()
    {
        return 'core_planbundle_planfiltertype_filter';
    }
}
