<?php

namespace Core\PlatoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class IngredienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('peso_unitario')
            ->add('categoria')
        ;
    }

    public function getName()
    {
        return 'core_platobundle_ingredientetype';
    }
}
