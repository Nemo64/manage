<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 22.02.15
 * Time: 21:28
 */

namespace ContactBundle\Entity;


use Doctrine\ORM\EntityRepository;

class ContactRepository extends EntityRepository
{
    /**
     * @return Contact[]
     */
    public function findAllSortedByName()
    {
        $qb = $this->createQueryBuilder('contact');
        $qb->addOrderBy('contact.name.first', 'ASC');
        $qb->addOrderBy('contact.name.second', 'ASC');
        $qb->addOrderBy('contact.name.last', 'ASC');
        $qb->addOrderBy('contact.name.prefix', 'ASC');
        $qb->addOrderBy('contact.name.suffix', 'ASC');
        return $qb->getQuery()->getResult();
    }

}