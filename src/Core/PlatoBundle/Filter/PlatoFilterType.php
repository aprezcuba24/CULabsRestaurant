<?php

namespace Core\PlatoBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Extension\Type\TextFilterType;

class PlatoFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'filter_text', array(
                'condition_pattern' => TextFilterType::PATTERN_CONTAINS,
            ))
            ->add('destacado', 'filter_checkbox')
        ;
    }

    public function getName()
    {
        return 'core_platobundle_platofiltertype_filter';
    }
}
