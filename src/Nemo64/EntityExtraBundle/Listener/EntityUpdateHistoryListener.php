<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 07.03.15
 * Time: 20:33
 */

namespace Nemo64\EntityExtraBundle\Listener;


use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Nemo64\EntityExtraBundle\Entity\UpdateHistory;

class EntityUpdateHistoryListener extends AbstractPropertyListener
{
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $className = get_class($entity);

            $properties = $this->getRelatedProperties($className);
            if (empty($properties)) {
                continue;
            }

            $changeSet = $uow->getEntityChangeSet($entity);
            $historyEntry = new UpdateHistory\UpdateHistoryEntry($className, $changeSet);
            $uow->persist($historyEntry);
            $uow->computeChangeSet($em->getClassMetadata(get_class($historyEntry)), $historyEntry);

            foreach ($properties as $property) {

                // is there a way to do this with property access?
                $propertyNameSingular = Inflector::singularize($property->name);
                $entity->{"add$propertyNameSingular"}($historyEntry);
            }

            $classMetadata = $em->getClassMetadata($className);
            $uow->recomputeSingleEntityChangeSet($classMetadata, $entity);
        }
    }
}