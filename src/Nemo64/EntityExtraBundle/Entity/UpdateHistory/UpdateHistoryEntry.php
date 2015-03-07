<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 07.03.15
 * Time: 20:11
 */

namespace Nemo64\EntityExtraBundle\Entity\UpdateHistory;


use Nemo64\EntityExtraBundle\Entity\DatabaseFields;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="entity_update_history")
 */
class UpdateHistoryEntry
{
    use DatabaseFields;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $className;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    private $changeSet;

    /**
     * @param string $className
     * @param array $changeSet
     */
    public function __construct($className, array $changeSet)
    {
        $this->className = $className;
        $this->changeSet = $changeSet;
    }

    /**
     * @return array
     */
    public function getChangeSet()
    {
        return $this->changeSet;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }
}