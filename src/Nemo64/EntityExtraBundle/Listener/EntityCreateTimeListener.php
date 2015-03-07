<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 07.03.15
 * Time: 16:46
 */

namespace Nemo64\EntityExtraBundle\Listener;


use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class EntityCreateTimeListener
{
    /**
     * @var \ReflectionProperty[][]
     */
    private $createTimeProperties;

    /**
     * @var PropertyAccessor
     */
    private $propertyAccessor;

    public function __construct()
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param string $className
     * @param string $property
     */
    public function addCreateTimeProperty($className, $property)
    {
        if (!isset($this->createTimeProperties[$className])) {
            $this->createTimeProperties[$className] = array();
        }

        $reflectionProperty = new \ReflectionProperty($className, $property);
        $reflectionProperty->setAccessible(true);
        $this->createTimeProperties[$className][] = $reflectionProperty;
    }

    /**
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            $className = get_class($entity);

            if (!isset($this->createTimeProperties[$className])) {
                continue;
            }

            foreach ($this->createTimeProperties[$className] as $createTimeProperty) {
                $createTimeProperty->setValue($entity, new \DateTime());
            }

            $uow->recomputeSingleEntityChangeSet($em->getClassMetadata($className), $entity);
        }
    }
}