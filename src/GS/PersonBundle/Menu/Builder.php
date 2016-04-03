<?php

namespace GS\PersonBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{

    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        $menu->addChild('Personnes')->setAttribute('dropdown', true);
        $menu['Personnes']->addChild('Liste des personnes', array(
            'route' => 'gs_person_homepage',
        ));
        $menu['Personnes']->addChild('Ajouter une personne', array(
            'route' => 'gs_person_add',
        ));

        return $menu;
    }

}
