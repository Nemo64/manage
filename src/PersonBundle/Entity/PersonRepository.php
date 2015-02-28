<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 22.02.15
 * Time: 21:28
 */

namespace PersonBundle\Entity;


use Doctrine\ORM\EntityRepository;

class PersonRepository extends EntityRepository
{
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        if ($orderBy === null) {
            $orderBy = array();
        }

        if (!isset($orderBy['name.complete'])) {
            $orderBy['name.complete'] = 'ASC';
        }

        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }
}