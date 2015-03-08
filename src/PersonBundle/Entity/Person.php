<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 15.02.15
 * Time: 02:54
 */
namespace PersonBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Selectable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Hateoas\Configuration\Annotation as Hateoas;
use Nemo64\EntityExtraBundle\Entity\DatabaseFields;
use Nemo64\EntityExtraBundle\Entity\UpdateHistory;
use PersonBundle\Entity\Person\Employment;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="PersonBundle\Entity\PersonRepository")
 * @ORM\Table(indexes={
 *  @ORM\Index(name="name_idx", columns={"name_complete"})
 * })
 *
 * @JMS\ExclusionPolicy("all")
 * @JMS\AccessType("public_method")
 *
 * @Hateoas\Relation("self", href=@Hateoas\Route(
 *      "get_person", parameters={"person": "expr(object.getId())"}
 * ))
 * @Hateoas\Relation("employments", href=@Hateoas\Route(
 *      "get_person_employments", parameters={"person": "expr(object.getId())"}
 * ))
 */
class Person
{
    use DatabaseFields;
    use UpdateHistory;

    /**
     * @var Person\Name
     *
     * @ORM\Embedded(class="PersonBundle\Entity\Person\Name")
     * @JMS\Type("PersonBundle\Entity\Person\Name")
     * @JMS\Expose()
     *
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    private $name;

    /**
     * @var Employment[]|Collection|Selectable
     *
     * @ORM\OneToMany(targetEntity="PersonBundle\Entity\Person\Employment", mappedBy="person", cascade={"ALL"}, orphanRemoval=true)
     * @JMS\ReadOnly()
     *
     * @Assert\Valid()
     */
    private $employments;

    public function __construct()
    {
        $this->employments = new ArrayCollection();
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

    /**
     * @return Person\Employment[]
     */
    public function getEmployments()
    {
        return $this->employments->toArray();
    }

    /**
     * @param Employment $employment
     * @return bool
     */
    public function addEmployment(Employment $employment)
    {
        if ($employment->getPerson() !== $this) {
            throw new \RuntimeException("$employment does not belong to $this");
        }

        if ($this->employments->contains($employment)) {
            return false;
        }

        if (!$this->employments->add($employment)) {
            return false;
        }

        return true;
    }

    /**
     * @param Employment $employment
     * @return bool
     */
    public function removeEmployment(Employment $employment)
    {
        return $this->employments->removeElement($employment);
    }
}