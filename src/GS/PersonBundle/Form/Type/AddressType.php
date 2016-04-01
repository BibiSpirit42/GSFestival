<?php

namespace GS\PersonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('street', TextType::class, array(
                    'label' => 'address.street',
                ))
                ->add('street2', TextType::class, array(
                    'label' => 'address.street2',
                    'required' => false
                ))
                ->add('zipCode', TextType::class, array(
                    'label' => 'address.zipCode',
                ))
                ->add('city', TextType::class, array(
                    'label' => 'address.city',
                ))
                ->add('county', TextType::class, array(
                    'label' => 'address.county',
                    'required' => false
                ))
                ->add('state', TextType::class, array(
                    'label' => 'address.state',
                    'required' => false
                ))
                ->add('country', CountryType::class, array(
                    'label' => 'address.country',
                ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GS\PersonBundle\Entity\Address',
        ));
    }

}
