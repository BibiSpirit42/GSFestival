<?php

namespace GS\FestivalBundle\Form\Type;

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
use GS\PersonBundle\Form\Type\PersonType;

class RegistrationType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('person', PersonType::class, array(
                    'label' => 'registration.person'
                ))
                ->add('role', ChoiceType::class, array(
                    'label' => 'registration.role.label',
                    'choices' => array(
                        'registration.role.leader' => true,
                        'registration.role.follower' => false,
                    ),
                ))
                ->add('level', EntityType::class, array(
                    'label' => 'registration.level',
                    'class' => 'GSFestivalBundle:Level',
                    'choice_label' => 'name',
                    'choices' => $options['festival']->getLevels(),
                ))
                ->add('offerHousing', CheckboxType::class, array(
                    'label' => 'registration.offerHousing',
                    'required' => false
                ))
                ->add('doubleBeds', IntegerType::class, array(
                    'label' => 'registration.doubleBeds'
                ))
                ->add('singleBeds', IntegerType::class, array(
                    'label' => 'registration.singleBeds'
                ))
                ->add('requestHousing', CheckboxType::class, array(
                    'label' => 'registration.requestHousing',
                    'required' => false
                ))
                ->add('shareBed', TextType::class, array(
                    'label' => 'registration.shareBed',
                    'required' => false
                ))
                ->add('roommates', TextareaType::class, array(
                    'label' => 'registration.roommates',
                    'required' => false
                ))
                ->add('comments', TextareaType::class, array(
                    'label' => 'registration.comments',
                    'required' => false
                ))
                ->add('partnerFirstName', TextType::class, array(
                    'label' => 'registration.partner.firstName',
                    'required' => false
                ))
                ->add('partnerLastName', TextType::class, array(
                    'label' => 'registration.partner.lastName',
                    'required' => false
                ))
                ->add('partnerEmail', TextType::class, array(
                    'label' => 'registration.partner.email',
                    'required' => false
                ))
                ->add('submit', SubmitType::class, array(
                    'label' => 'registration.submit',
                    'position' => 'last'
                ))
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
