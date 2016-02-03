<?php

namespace GS\FestivalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
                ->add('envoyer', SubmitType::class)
        ;

//        $builder
//                ->add('status', ChoiceType::class, array(
//                    'choices' => array(
//                        'Received' => 'status_received',
//                        'Waiting list' => 'status_waiting_list',
//                        'Validated' => 'status_validated',
//                        'Paid' => 'status_paid',
//                        'Cancelled' => 'status_cancelled',
//                    ),
//                ))
//        ;
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
