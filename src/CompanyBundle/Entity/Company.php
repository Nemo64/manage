<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 28.02.15
 * Time: 17:23
 */

namespace CompanyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Hateoas\Configuration\Annotation as Hateoas;
use Nemo64\EntityExtraBundle\Entity\DatabaseFields;
use Nemo64\EntityExtraBundle\Entity\GenericName;
use Nemo64\EntityExtraBundle\Entity\UpdateHistory;
use PersonBundle\Entity\Person;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="CompanyBundle\Entity\CompanyRepository")
 * @ORM\Table(indexes={
 *  @ORM\Index(name="name_idx", columns={"name"})
 * })
 *
 * @JMS\ExclusionPolicy("all")
 * @JMS\AccessType("public_method")
 *
 * @Hateoas\Relation("self", href=@Hateoas\Route(
 *      "get_company", parameters={"company": "expr(object.getId())"}
 * ))
 * @Hateoas\Relation("employees", href=@Hateoas\Route(
 *      "get_company_employees", parameters={"company": "expr(object.getId())"}
 * ))
 */
class Company
{
    use DatabaseFields;
    use UpdateHistory;
    use GenericName;

    /**
     * @var Collection|Person[]
     *
     * @ORM\ManyToMany(targetEntity="PersonBundle\Entity\Person", inversedBy="employedByCompanies")
     * @ORM\OrderBy({"name.complete": "ASC"})
     * @JMS\Exclude()
     * @JMS\AccessType("property")
     */
    private $employees;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
    }

    /**
     * @return Person[]
     */
    public function getEmployees()
    {
        return $this->employees->toArray();
    }

    /**
     * @param Person $person
     * @return bool
     */
    public function addEmployee(Person $person)
    {
        if ($this->employees->contains($person)) {
            return false;
        }

        if (!$this->employees->add($person)) {
            return false;
        }

        $person->addEmployedByCompany($this);
        return true;
    }

    /**
     * @param Person $person
     * @return bool
     */
    public function removeEmployee(Person $person)
    {
        if (!$this->employees->removeElement($person)) {
            return false;
        }

        $person->removeEmployedByCompany($this);
        return true;
    }
}