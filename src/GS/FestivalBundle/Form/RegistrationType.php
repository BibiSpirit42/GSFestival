<?php

namespace GS\FestivalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use GS\PersonBundle\Form\PersonType;

class RegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('person', PersonType::class)
                ->add('role', ChoiceType::class, array(
                    'choices' => array(
                        'Guideur/Cavalier' => true,
                        'Suiveur/Cavaliere' => false,
                    ),
                ))
                ->add('level', EntityType::class, array(
                    'class' => 'GSFestivalBundle:Level',
                    'choice_label' => 'name',
                    'choices' => $options['festival']->getLevels(),
                ))
                ->add('offerHousing', CheckboxType::class, array('required' => false))
                ->add('doubleBeds', IntegerType::class)
                ->add('singleBeds', IntegerType::class)
                ->add('comments', TextareaType::class, array('required' => false))
                ->add('partnerFirstName', TextType::class, array('required' => false))
                ->add('partnerLastName', TextType::class, array('required' => false))
                ->add('partnerEmail', TextType::class, array('required' => false))
                ->add('envoyer', SubmitType::class, array('position' => 'last'))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'festival',
        ));
        
        $resolver->setAllowedTypes('festival', 'GS\FestivalBundle\Entity\Festival');

        $resolver->setDefaults(array(
            'data_class' => 'GS\FestivalBundle\Entity\Registration',
            'festival' => null,
        ));
    }

}
