<?php

namespace GS\FestivalBundle\Menu;

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

        $menu->addChild('Festivals')->setAttribute('dropdown', true);
        $menu['Festivals']->addChild('Liste des festivals', array(
            'route' => 'gs_festival_homepage',
        ));
        $menu['Festivals']->addChild('Ajouter un festival', array(
            'route' => 'gs_festival_add',
        ));
        $menu['Festivals']->addChild('Liste des inscriptions', array(
            'route' => 'gs_registration_index',
        ));

        return $menu;
    }

}
