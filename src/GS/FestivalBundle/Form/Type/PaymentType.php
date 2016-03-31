<?php

namespace GS\FestivalBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PaymentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('amount', NumberType::class, array(
                    'scale' => 2,
                ))
                ->add('type', ChoiceType::class, array(
                    'choices' => array(
                        'Virement' => 'transfer',
                        'Cheque' => 'check',
                        'Paypal' => 'paypal',
                        'Liquide' => 'cash'
                    )
                ))
                ->add('validate', SubmitType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GS\FestivalBundle\Entity\Payment',
        ));
    }
}
