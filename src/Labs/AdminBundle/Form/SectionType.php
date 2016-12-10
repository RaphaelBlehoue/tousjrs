<?php

namespace Labs\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SectionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => false, 'attr' => array('class'=>'form-control', 'placeholder'=> 'Nom du menu')))
            ->add('color', TextType::class, array('label' => false, 'attr' => array('class'=>'form-control', 'placeholder'=> 'Code couleur')))
            ->add('online', ChoiceType::class, array(
                'label' => false,
                'attr'  => array('class' => 'form-control'),
                'placeholder' => 'Choix du status',
                'empty_data'  => null,
                'choices' => array(
                    'En ligne' => true,
                    'Hors ligne' => false
                )))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Labs\AdminBundle\Entity\Section'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'labs_adminbundle_section';
    }


}
