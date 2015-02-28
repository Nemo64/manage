<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 28.02.15
 * Time: 17:23
 */

namespace CompanyBundle\Entity;

use AppBundle\Entity\EntityFields;
use AppBundle\Entity\GenericName;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="PersonBundle\Entity\PersonRepository")
 * @ORM\Table(indexes={
 *  @ORM\Index(name="name_idx", columns={"name"})
 * })
 *
 * @JMS\ExclusionPolicy("all")
 * @JMS\AccessType("public_method")
 */
class Company
{
    use EntityFields;
    use GenericName;
}