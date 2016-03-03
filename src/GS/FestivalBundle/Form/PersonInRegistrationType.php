<?php

namespace GS\FestivalBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

class PersonInRegistrationType extends PersonType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->remove('save');
    }

}
