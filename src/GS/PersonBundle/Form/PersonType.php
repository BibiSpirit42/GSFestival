<?php

namespace GS\PersonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use libphonenumber\PhoneNumberFormat;

use GS\PersonBundle\Form\AddressType;

class PersonType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('email', TextType::class)
                ->add('firstName', TextType::class)
                ->add('lastName', TextType::class)
                ->add('address', AddressType::class)
                ->add('phoneNumber', PhoneNumberType::class, array('default_region' => 'FR', 'format' => PhoneNumberFormat::NATIONAL))
        ;
       
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GS\PersonBundle\Entity\Person',
        ));
    }

}
