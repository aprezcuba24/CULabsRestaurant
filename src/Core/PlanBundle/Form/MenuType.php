<?php

namespace Core\PlanBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('foto_file', null, array(
                'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                'required'   => $options['foto_reqerida'],
            ))
            ->add('resumen')
            ->add('descripcion')
            ->add('platos')
            ->add('categoria')
        ;
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => 'Core\PlanBundle\Entity\Menu',
            'foto_reqerida' => true,
        ));
    }
    public function getName()
    {
        return 'core_planbundle_menutype';
    }
}
