<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 07.03.15
 * Time: 21:09
 */

namespace Nemo64\EntityExtraBundle\Type\WeakRelationType;


use Doctrine\Common\EventArgs;

abstract class AbstractWeakRelationEventArgs extends EventArgs
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @var array
     */
    protected $identifier;

    /**
     * @var object
     */
    protected $entity;

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return array
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return object
     */
    public function getEntity()
    {
        return $this->entity;
    }
}