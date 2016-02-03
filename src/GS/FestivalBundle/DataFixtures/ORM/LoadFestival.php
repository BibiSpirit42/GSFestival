<?php

namespace GS\FestivalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use GS\FestivalBundle\Entity\Festival;
use GS\FestivalBundle\Entity\Level;
use GS\FestivalBundle\Entity\Registration;

class LoadCategory implements FixtureInterface
{

    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        $festival = new Festival();
        $festival->setName('GSDF');
        $festival->setDescription('test');
        $festival->setLocation('Grenoble');
        $festival->setDate(new \Datetime('2016-11-10'));

        $level1 = new Level();
        $level1->setName('Flocon');
        $level1->setDescription('toto');
        $level1->setCapacity(15);
        $level1->setExtraPerson(2);
        $level1->setPrice(80.0);

        $level2 = new Level();
        $level2->setName('1ere Etoile');
        $level2->setDescription('titi');
        $level2->setCapacity(17);
        $level2->setExtraPerson(2);
        $level2->setPrice(150.0);

        for ($i = 0; $i < 10; $i++) {
            $registration = new Registration();
            if ($i % 2) {
                $registration->setRole(True);
            } else {
                $registration->setRole(False);
            }
            $level1->addRegistration($registration);
        }

        for ($i = 0; $i < 13; $i++) {
            $registration = new Registration();
            if ($i % 2) {
                $registration->setRole(True);
            } else {
                $registration->setRole(False);
            }
            $level2->addRegistration($registration);
        }

        $festival->addLevel($level1);
        $festival->addLevel($level2);

        $manager->persist($festival);
        $manager->flush();
    }

}
