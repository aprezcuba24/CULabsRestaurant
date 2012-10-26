<?php

namespace Core\PlatoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlatoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    { 
        $builder
            ->add('name')
            ->add('destacado')
            ->add('forma_elaboracion')
            ->add('datos_nutricionales')
            ->add('resumen')
            ->add('descripcion')
            ->add('foto_file', null, array(
                'required' => $options['foto_reqerida'],
            ))
            ->add('plato_ingredientes', 'collection', array(
                'type'         => new PlatoIngredienteType(),
                'allow_add'    => true,
                'by_reference' => false,
                'allow_delete' => true,
            ))
        ;
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => 'Core\PlatoBundle\Entity\Plato',
            'foto_reqerida' => true,
        ));
    }
    public function getName()
    {
        return 'core_platobundle_platotype';
    }
}
