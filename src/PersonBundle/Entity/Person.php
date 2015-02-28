<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 15.02.15
 * Time: 02:54
 */
namespace PersonBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="PersonBundle\Entity\PersonRepository")
 * @ORM\Table(indexes={
 *  @ORM\Index(name="full_name_idx", columns={"name__complete"})
 * })
 *
 * @JMS\ExclusionPolicy("all")
 * @JMS\AccessType("public_method")
 *
 * @Hateoas\Relation("self", href=@Hateoas\Route("get_person", parameters={"person": "expr(object.getId())"}))
 */
class Person
{
    /**
     * @var int|null
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id()
     * @ORM\Column(type="integer")
     *
     * @JMS\Expose()
     * @JMS\ReadOnly()
     */
    private $id;

    /**
     * @var Person\Name
     *
     * @ORM\Embedded(class="PersonBundle\Entity\Person\Name")
     * @JMS\Expose()
     *
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    private $name;

    public function __construct()
    {

    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Person\Name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Person\Name $name
     */
    public function setName(Person\Name $name)
    {
        $this->name = $name;
    }
}