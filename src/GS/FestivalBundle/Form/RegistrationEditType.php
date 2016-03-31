<?php

namespace GS\FestivalBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class RegistrationEditType extends RegistrationType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
                ->add('partner', EntityType::class, array(
                    'label' => 'registration.partner.partner',
                    'class' => 'GSFestivalBundle:Registration',
                    'choice_label' => 'displayName',
                    'choices' => $options['partners'],
                    'required' => false,
                    'empty_data' => null,
                ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        
        $resolver->setRequired(array(
            'partners',
        ));

        $resolver->setDefaults(array(
            'partners' => null,
        ));
    }

}
