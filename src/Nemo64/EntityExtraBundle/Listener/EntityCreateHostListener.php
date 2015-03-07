<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 07.03.15
 * Time: 19:58
 */

namespace Nemo64\EntityExtraBundle\Listener;


class EntityCreateHostListener extends AbstractPropertyCreateListener
{
    /**
     * Gets the value the property should have while the entity is created.
     * If the value is null it will not be set.
     *
     * @param object $entity
     * @param \ReflectionProperty $property
     * @return mixed|null
     */
    protected function getCreateValue($entity, \ReflectionProperty $property)
    {
        return gethostname();
    }
}