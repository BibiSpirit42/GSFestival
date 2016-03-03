<?php

namespace GS\FestivalBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
                    'class' => 'GSFestivalBundle:Registration',
                    'choice_label' => 'displayName',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->leftJoin('u.person', 'per')
                                ->addSelect('per')
                                ->orderBy('per.lastName', 'ASC');
                    },
                    'required' => false,
                    'empty_data' => null,
                ))
        ;
    }

}
