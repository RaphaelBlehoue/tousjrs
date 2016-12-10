<?php

namespace Labs\AdminBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => false, 'attr' => array('class'=>'form-control', 'placeholder'=> 'Nom du sous menu')))
            ->add('online', ChoiceType::class, array(
                'label' => false,
                'attr'  => array('class' => 'form-control'),
                'placeholder' => 'Choix du status',
                'empty_data'  => null,
                'choices' => array(
                    'En ligne' => true,
                    'Hors ligne' => false
                )))
            ->add('section', EntityType::class, array(
                'class' => 'LabsAdminBundle:Section',
                'choice_label' => 'name',
                'label' => false,
                'attr' => array('class' => 'form-control'),
                'placeholder' => 'Choix de la rubrique',
                'empty_data'  => null
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Labs\AdminBundle\Entity\Item'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'labs_adminbundle_item';
    }


}
