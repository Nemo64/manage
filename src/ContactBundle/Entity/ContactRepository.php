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
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        if ($orderBy === null) {
            $orderBy = array();
        }

        if (!isset($orderBy['name.first'])) {
            $orderBy['name.first'] = 'ASC';
        }

        if (!isset($orderBy['name.second'])) {
            $orderBy['name.second'] = 'ASC';
        }

        if (!isset($orderBy['name.last'])) {
            $orderBy['name.last'] = 'ASC';
        }

        if (!isset($orderBy['name.prefix'])) {
            $orderBy['name.prefix'] = 'ASC';
        }

        if (!isset($orderBy['name.suffix'])) {
            $orderBy['name.suffix'] = 'ASC';
        }

        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }
}