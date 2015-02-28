<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 28.02.15
 * Time: 17:29
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

trait GenericName
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=60)
     * @JMS\Expose()
     * @JMS\ReadOnly()
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=60)
     * @Assert\Regex(pattern="/^[^\p{C}]*$/u")
     */
    private $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string)$name;
    }
}