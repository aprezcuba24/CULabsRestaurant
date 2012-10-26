<?php

namespace Core\PlatoBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Extension\Type\TextFilterType;

class IngredienteFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'filter_text', array(
                'condition_pattern' => TextFilterType::PATTERN_CONTAINS,
            ))
            ->add('peso_unitario', 'filter_number')
        ;
    }

    public function getName()
    {
        return 'core_platobundle_ingredientefiltertype_filter';
    }
}
