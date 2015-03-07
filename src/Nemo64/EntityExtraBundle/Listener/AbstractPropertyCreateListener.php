<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 07.03.15
 * Time: 19:09
 */

namespace Nemo64\EntityExtraBundle\Listener;


use Doctrine\ORM\Event\OnFlushEventArgs;

abstract class AbstractPropertyCreateListener extends AbstractPropertyListener
{
    /**
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $this->stopwatch->start(get_class($this));

        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {

            $className = get_class($entity);
            $properties = $this->getRelatedProperties($className);

            if (empty($properties)) {
                continue;
            }

            $setCount = 0;
            foreach ($properties as $property) {

                $value = $this->getCreateValue($entity, $property);
                if ($value === null) {
                    continue;
                }

                $property->setValue($entity, $value);
                $setCount++;
            }

            if ($setCount > 0) {
                // TODO this may be called too often as every change listener calls this individually
                $classMetadata = $em->getClassMetadata($className);
                $uow->recomputeSingleEntityChangeSet($classMetadata, $entity);
            }
        }

        $this->stopwatch->stop(get_class($this));
    }

    /**
     * Gets the value the property should have while the entity is created.
     * If the value is null it will not be set.
     *
     * @param object $entity
     * @param \ReflectionProperty $property
     * @return mixed|null
     */
    abstract protected function getCreateValue($entity, \ReflectionProperty $property);
}