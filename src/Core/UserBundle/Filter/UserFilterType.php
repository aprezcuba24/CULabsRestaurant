<?php

namespace Core\UserBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Extension\Type\TextFilterType;

class UserFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'filter_text', array(
                'condition_pattern' => TextFilterType::PATTERN_CONTAINS,
            ))
            ->add('email', 'filter_text', array(
                'condition_pattern' => TextFilterType::PATTERN_CONTAINS,
            ))
        ;
    }

    public function getName()
    {
        return 'core_userbundle_userfiltertype_filter';
    }
}
