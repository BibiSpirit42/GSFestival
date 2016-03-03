<?php

namespace GS\FestivalBundle\Repository;

use GS\FestivalBundle\Entity\Level;
use GS\FestivalBundle\Entity\Registration;

/**
 * RegistrationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RegistrationRepository extends \Doctrine\ORM\EntityRepository
{

    public function getSortedForLevel(Level $level)
    {
        $leaders = $this->getLeadersForLevel($level);
        $followers = $this->getFollowersForLevel($level);

        $result = array();
        foreach ($leaders as $leader) {
            $result[] = array($leader, $this->getAssociatedFollower($leader, $followers));
        }
        
        foreach ($followers as $follower) {
            $result[] = [null, $follower];
        }
        
        return $result;
    }

    private function getAssociatedFollower(Registration $leader, array &$followers)
    {
        $result = null;
        if (null !== $leader->getPartner()) {
            $result = $leader->getPartner();
            if (($key = array_search($leader->getPartner(), $followers)) !== false) {
                unset($followers[$key]);
            }
        } elseif ($followers) {
            $result = array_shift($followers);
        }
        return $result;
    }

    public function getLeadersForLevel(Level $level)
    {
        $qb = $this->createQueryBuilder('a');
        $qb
                ->where('a.level = :level')
                ->setParameter('level', $level)
                ->andWhere('a.role = :isTrue')
                ->setParameter('isTrue', true);

        return $qb->getQuery()->getResult();
    }

    public function getFollowersForLevel(Level $level)
    {
        $qb = $this->createQueryBuilder('a');
        $qb
                ->where('a.level = :level')
                ->setParameter('level', $level)
                ->andWhere('a.role = :isFalse')
                ->setParameter('isFalse', false);

        return $qb->getQuery()->getResult();
    }

}
