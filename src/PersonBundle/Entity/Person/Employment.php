<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 07.03.15
 * Time: 23:39
 */

namespace PersonBundle\Entity\Person;


use CompanyBundle\Entity\Company;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Hateoas\Configuration\Annotation as Hateoas;
use Nemo64\EntityExtraBundle\Entity\DatabaseFields;
use Nemo64\EntityExtraBundle\Entity\UpdateHistory;
use PersonBundle\Entity\Person;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table()
 *
 * @JMS\ExclusionPolicy("all")
 * @JMS\AccessType("public_method")
 *
 * @Hateoas\Relation("person", href=@Hateoas\Route(
 *      "get_employment_person", parameters={"employment": "expr(object.getId())"}
 * ))
 * @Hateoas\Relation("company", href=@Hateoas\Route(
 *      "get_employment_company", parameters={"employment": "expr(object.getId())"}
 * ))
 */
class Employment
{
    use DatabaseFields;
    use UpdateHistory;

    /**
     * @var Person
     *
     * @ORM\ManyToOne(targetEntity="PersonBundle\Entity\Person", inversedBy="employments")
     * @JMS\ReadOnly()
     *
     * @Assert\NotNull()
     */
    private $person;

    /**
     * @var Company
     *
     * @ORM\ManyToOne(targetEntity="CompanyBundle\Entity\Company")
     * @JMS\ReadOnly()
     *
     * @Assert\NotNull()
     */
    private $company;

    public function __construct(Person $person, Company $company)
    {
        $this->person = $person;
        $this->company = $company;

        $person->addEmployment($this);
        $company->addEmployment($this);
    }

    /**
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }
}