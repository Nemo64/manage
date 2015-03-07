<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 07.03.15
 * Time: 21:02
 */

namespace Nemo64\EntityExtraBundle\Type\WeakRelationType;


use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAware;

class WeakRelationListener extends ContainerAware
{
    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }

    public function onWeakRelationDeserialize(WeakRelationDeserializeEventArgs $args)
    {
        $manager = $this->getEntityManager();
        $entity = $manager->getReference($args->getClassName(), $args->getIdentifier());
        $args->setEntity($entity);
    }

    public function onWeakRelationSerialize(WeakRelationSerializeEventArgs $args)
    {
        $manager = $this->getEntityManager();
        $metadata = $manager->getClassMetadata($args->getClassName());
        $args->setIdentifier($metadata->getIdentifierValues($args->getEntity()));
    }
}