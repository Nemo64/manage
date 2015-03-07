<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 07.03.15
 * Time: 20:56
 */

namespace Nemo64\EntityExtraBundle\Type\WeakRelationType;


use Doctrine\Common\Proxy\Proxy;

class WeakRelationDeserializeEventArgs extends AbstractWeakRelationEventArgs
{
    /**
     * @param $className
     * @param array $identifier
     */
    public function __construct($className, array $identifier)
    {
        $this->className = $className;
        $this->identifier = $identifier;
    }

    /**
     * @param Proxy $entity
     */
    public function setEntity(Proxy $entity)
    {
        if ($this->entity !== null) {
            throw new \LogicException("#setEntity should only be called once to create it");
        }

        $this->entity = $entity;
    }
}