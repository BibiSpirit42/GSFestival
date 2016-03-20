<?php

namespace GS\FestivalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use GS\FestivalBundle\Entity\Festival;
use GS\FestivalBundle\Entity\Level;
use GS\FestivalBundle\Entity\Registration;
use GS\PersonBundle\Entity\Person;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadCategory implements FixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

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

        for ($i = 0; $i < 15; $i++) {
            $phoneNumber = $this->container->get('libphonenumber.phone_number_util')->parse('0380581981', 'FR');
            $person = new Person();
            $person->setFirstName('Toto'.$i);
            $person->setLastName('Titi'.$i);
            $person->setEmail('bibi'.$i.'@gmail.com');
            $person->setPhoneNumber($phoneNumber);

            $registration = new Registration();
            if ($i % 2 || $i > 10) {
                $registration->setRole(True);
            } else {
                $registration->setRole(False);
            }
            $person->addRegistration($registration);
            $level1->addRegistration($registration);
        }

        for ($i = 0; $i < 15; $i++) {
            $person = new Person();
            $person->setFirstName('Tata'.$i);
            $person->setLastName('Tutu'.$i);
            $person->setEmail('baba'.$i.'@gmail.com');

            $registration = new Registration();
            if ($i % 2 && $i < 11) {
                $registration->setRole(True);
            } else {
                $registration->setRole(False);
            }
            $person->addRegistration($registration);
            $level2->addRegistration($registration);
        }

        $festival->addLevel($level1);
        $festival->addLevel($level2);

        $manager->persist($festival);
        $manager->flush();
    }

}
