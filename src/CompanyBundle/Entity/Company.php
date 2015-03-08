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
use Doctrine\Common\Collections\Selectable;
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
 * @Hateoas\Relation("employments", href=@Hateoas\Route(
 *      "get_company_employments", parameters={"company": "expr(object.getId())"}
 * ))
 */
class Company
{
    use DatabaseFields;
    use UpdateHistory;
    use GenericName;

    /**
     * @var Person\Employment[]|Collection|Selectable
     *
     * @ORM\OneToMany(targetEntity="PersonBundle\Entity\Person\Employment", mappedBy="company", cascade={"ALL"}, orphanRemoval=true)
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
     * @return Person\Employment[]
     */
    public function getEmployments()
    {
        return $this->employments->toArray();
    }

    /**
     * @param Person\Employment $employment
     * @return bool
     */
    public function addEmployment(Person\Employment $employment)
    {
        if ($employment->getCompany() !== $this) {
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
     * @param Person\Employment $employment
     * @return bool
     */
    public function removeEmployment(Person\Employment $employment)
    {
        return $this->employments->removeElement($employment);
    }
}