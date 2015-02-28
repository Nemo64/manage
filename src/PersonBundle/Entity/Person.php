<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 15.02.15
 * Time: 02:54
 */
namespace PersonBundle\Entity;


use AppBundle\Entity\EntityFields;
use CompanyBundle\Entity\Company;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Hateoas\Configuration\Annotation as Hateoas;
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
 * @Hateoas\Relation("employedByCompanies", href=@Hateoas\Route(
 *      "get_person_employed", parameters={"person": "expr(object.getId())"}
 * ))
 */
class Person
{
    use EntityFields;

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

    /**
     * @var Collection|Company[]
     *
     * @ORM\ManyToMany(targetEntity="CompanyBundle\Entity\Company", mappedBy="employees")
     * @ORM\OrderBy({"name": "ASC"})
     * @JMS\Exclude()
     * @JMS\AccessType("property")
     */
    private $employedByCompanies;

    public function __construct()
    {
        $this->employedByCompanies = new ArrayCollection();
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
     * @return Company[]
     */
    public function getEmployedByCompanies()
    {
        return $this->employedByCompanies->toArray();
    }

    /**
     * @param Company $company
     * @return bool
     */
    public function addEmployedByCompany(Company $company)
    {
        if ($this->employedByCompanies->contains($company)) {
            return false;
        }

        if (!$this->employedByCompanies->add($company)) {
            return false;
        }

        $company->addEmployee($this);
        return true;
    }

    /**
     * @param Company $company
     * @return bool
     */
    public function removeEmployedByCompany(Company $company)
    {
        if (!$this->employedByCompanies->removeElement($company)) {
            return false;
        }

        $company->removeEmployee($this);
        return true;
    }
}