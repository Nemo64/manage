<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 07.03.15
 * Time: 16:35
 */

namespace Nemo64\EntityExtraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Nemo64\EntityExtraBundle\Annotation as Extra;

trait DatabaseFields
{
    /**
     * @var int|null
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @JMS\Expose()
     * @JMS\ReadOnly()
     * @JMS\AccessType("public_method")
     */
    private $id;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime")
     * @JMS\Exclude()
     * @JMS\ReadOnly()
     * @JMS\AccessType("public_method")
     *
     * @Extra\CreateTime()
     */
    private $createTime;

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $className = preg_replace('/^.*\\/', '', get_class($this));
        return "$className:" . $this->getId();
    }
}