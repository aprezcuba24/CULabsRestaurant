<?php

namespace Core\PlatoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlatoIngredienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ingrediente')
            ->add('cantidad')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Core\PlatoBundle\Entity\PlatoIngrediente'
        ));
    }

    public function getName()
    {
        return 'core_platobundle_platoingredientetype';
    }
}
