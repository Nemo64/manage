<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 28.02.15
 * Time: 17:26
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

trait EntityFields
{
    /**
     * @var int|null
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @JMS\Expose()
     * @JMS\ReadOnly()
     */
    private $id;

    public function getId()
    {
        return $this->id;
    }
}