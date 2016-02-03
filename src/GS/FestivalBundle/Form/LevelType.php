<?php

namespace GS\FestivalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LevelType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', TextType::class)
                ->add('description', TextareaType::class)
                ->add('solo', CheckboxType::class, array('required' => false))
                ->add('capacity', IntegerType::class)
                ->add('extraPerson', IntegerType::class)
                ->add('price', NumberType::class, array('scale' => 2))
        ;
        
        if ( $options['btn_summit'] ) {
            $builder->add('save', SubmitType::class);
        }
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GS\FestivalBundle\Entity\Level',
            'btn_summit' => false,
        ));
    }
}
