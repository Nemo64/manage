<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 07.03.15
 * Time: 21:05
 */

namespace Nemo64\EntityExtraBundle\Type\WeakRelationType;


class WeakRelationSerializeEventArgs extends AbstractWeakRelationEventArgs
{
    public function __construct($entity)
    {
        $this->entity = $entity;
        $this->className = get_class($entity);
    }

    /**
     * @param array $identifier
     */
    public function setIdentifier($identifier)
    {
        if ($this->identifier !== null) {
            throw new \LogicException("#setEntity should only be called once to create it");
        }

        $this->identifier = $identifier;
    }
}