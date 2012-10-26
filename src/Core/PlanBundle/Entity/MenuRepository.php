<?php

namespace Core\PlanBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Core\PlanBundle\Entity\Plan;
use Core\PlanBundle\Entity\Momento;

/**
 * MenuRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MenuRepository extends EntityRepository
{
    public function findByPlanAndMomento(Plan $plan, Momento $momento)
    {
        return $this->createQueryBuilder('menu')
                    ->addSelect('plan_momento')
                    ->leftJoin('menu.plan_momentos', 'plan_momento')
                    ->leftJoin('plan_momento.plan', 'plan')
                    ->andWhere(sprintf('plan.id = %s', $plan->getId()))
                    ->leftJoin('plan_momento.momento', 'momento')
                    ->andWhere(sprintf('momento.id = %s', $momento->getId()))
               ;
    }
    public function getBySlug($slug)
    {
        return $this->createQueryBuilder('menu')
                    ->where(sprintf('menu.slug = \'%s\'', $slug))
                    ->leftJoin('menu.platos', 'plato')
                    ->addSelect('plato')
                    ->getQuery()
                    ->getOneOrNullResult()
               ;
    }
}
