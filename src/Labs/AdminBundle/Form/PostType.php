<?php

namespace Labs\AdminBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,array('label' => false, 'attr' => array('placeholder'=> 'Titre de l\'article')))
            ->add('content', CKEditorType::class, array(
                'label' => false
            ))
            ->add('online',ChoiceType::class, array(
                'label' => false,
                'choices' => array(
                        'En Ligne' => true,
                        'Hors ligne' => false
                )
            ))
            ->add('item',EntityType::class, array(
                'class' => 'LabsAdminBundle:Item',
                'choice_label' => 'name',
                'label' => false,
                'placeholder' => 'Choix de la Rubrique',
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
            'data_class' => 'Labs\AdminBundle\Entity\Post'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'labs_adminbundle_post';
    }


}
