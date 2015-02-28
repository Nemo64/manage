<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 15.02.15
 * Time: 02:54
 */
namespace ContactBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ContactBundle\Entity\ContactRepository")
 * @ORM\Table(indexes={
 *  @ORM\Index(name="full_name_idx", columns={"name_first", "name_last"})
 * })
 *
 * @JMS\ExclusionPolicy("all")
 * @JMS\AccessType("public_method")
 *
 * @Hateoas\Relation("self", href=@Hateoas\Route("get_contact", parameters={"contact": "expr(object.getId())"}))
 */
class Contact
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
     * @var Contact\Name
     *
     * @ORM\Embedded(class="ContactBundle\Entity\Contact\Name")
     *
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
     * @return Contact\Name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Contact\Name $name
     */
    public function setName(Contact\Name $name)
    {
        $this->name = $name;
    }
}