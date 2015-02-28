<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 15.02.15
 * Time: 03:18
 */

namespace PersonBundle\Entity\Person;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Embeddable()
 * @JMS\ExclusionPolicy("all")
 * @JMS\AccessType("public_method")
 */
class Name
{
    /**
     * This space won't break in an interface and is used to join name parts together.
     */
    const NO_BREAK_SPACE = "\xc2\xa0";

    /**
     * This expression should cover any character used in names.
     *
     * @see http://www.regular-expressions.info/unicode.html#category
     */
    const EXPR_VALIDATE = '/^[\p{L}\p{M}\p{Sk}\p{Zs}]*$/u';

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     * @JMS\Expose()
     * @JMS\ReadOnly()
     *
     * @Assert\Length(max=30)
     * @Assert\Regex(pattern=Name::EXPR_VALIDATE)
     */
    private $prefix;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=60, nullable=true)
     * @JMS\Expose()
     * @JMS\ReadOnly()
     *
     * @Assert\Length(min=2, max=60)
     * @Assert\Regex(pattern=Name::EXPR_VALIDATE)
     */
    private $first;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=60, nullable=true)
     * @JMS\Expose()
     * @JMS\ReadOnly()
     *
     * @Assert\Length(min=2, max=60)
     * @Assert\Regex(pattern=Name::EXPR_VALIDATE)
     */
    private $second;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=60, nullable=true)
     * @JMS\Expose()
     * @JMS\ReadOnly()
     *
     * @Assert\Length(min=2, max=60)
     * @Assert\Regex(pattern=Name::EXPR_VALIDATE)
     */
    private $last;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     * @JMS\Expose()
     * @JMS\ReadOnly()
     *
     * @Assert\Length(max=30)
     * @Assert\Regex(pattern=Name::EXPR_VALIDATE)
     */
    private $suffix;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=60, nullable=true)
     * @JMS\Expose()
     * @JMS\ReadOnly()
     *
     * @Assert\Length(min=1, max=60)
     * @Assert\Regex(pattern=Name::EXPR_VALIDATE)
     */
    private $nickname;

    /**
     * Cache value for the complete name. It makes database queries easier and prevents unneeded recalculation.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=240)
     * @JMS\AccessType("property")
     * @JMS\Exclude()
     */
    private $_complete;

    /**
     * @param null|string $last
     * @param null|string $first
     * @param null|string $second
     * @param null|string $nickname
     * @param null|string $prefix
     * @param null|string $suffix
     */
    public function __construct($last, $first = null, $second = null, $nickname = null, $prefix = null, $suffix = null)
    {
        $this->last = $last;
        $this->first = $first;
        $this->second = $second;
        $this->nickname = $nickname;
        $this->prefix = $prefix;
        $this->suffix = $suffix;
        $this->_complete = $this->computeCompleteName();
    }

    /**
     * Tries to interpret the name parts from a string.
     * This implementation may be faulty if names itself contain separators.
     *
     * @param string $str
     * @return Name
     * @todo test
     */
    public static function fromString($str)
    {
        $parts = preg_split('/\p{Z}/u', trim($str));
        $parts = array_map('trim', $parts);

        $length = count($parts);
        $firstname = reset($parts) ?: null;
        $lastname = $length > 1 ? end($parts) : null;
        $secondName = $length > 2 ? implode(" ", array_slice($parts, 1, -1)) : null;

        return new self($lastname, $firstname, $secondName);
    }

    /**
     * Same as normal but returns "[invalid name]" if this name is empty.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getComplete() ?: "[invalid name]";
    }

    /**
     * @Assert\Callback()
     *
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->isEmpty()) {
            static $message = "At least one part of the name is required";
            $context->buildViolation($message)->atPath('first')->addViolation();
            $context->buildViolation($message)->atPath('last')->addViolation();
            $context->buildViolation($message)->atPath('nickname')->addViolation();
        }
    }

    /**
     * A name can be called "empty", if it does not contain first-, last- or nickname.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return !$this->hasRealName() && !$this->hasNickname();
    }

    /**
     * A real name is then present, if first- or lastname are present.
     * The second name will be ignored in this check.
     *
     * @return bool
     */
    public function hasRealName()
    {
        $hasFirstname = strlen($this->getFirst()) > 0;
        $hasLastname = strlen($this->getLast()) > 0;
        return $hasFirstname && $hasLastname;
    }

    /**
     * This is simple check if nickname isn't empty.
     *
     * @return bool
     */
    public function hasNickname()
    {
        return strlen($this->getNickname()) > 0;
    }

    /**
     * Computes the complete name.
     *
     * @return null|string
     */
    protected function computeCompleteName()
    {
        if ($this->isEmpty()) {
            return null;
        }

        if (!$this->hasRealName()) {
            return $this->getNickname();
        }

        // this is easy: just throw all names together
        return $this->joinNameParts(array(
            $this->getPrefix(),
            $this->getFirst(),
            $this->getSecond(),
            $this->getLast(),
            $this->getSuffix()
        ));
    }

    /**
     * Creates a representation that is normally used.
     * The names will be included in the order from first to last.
     * If the user has no real name, the nickname will be used.
     *
     * @JMS\VirtualProperty()
     *
     * @return string|null
     * @see Name::hasRealName
     * @see Name::getNickname
     */
    public function getComplete()
    {
        return $this->_complete;
    }

    /**
     * Creates a professional representation of this name.
     * It will have the lastname in front of the other names and separates them using a comma
     * If the user has no real name, the nickname will be used.
     *
     * @JMS\VirtualProperty()
     *
     * @return null|string
     * @see Name::hasRealName
     * @see Name::getNickname
     */
    public function getProfessional()
    {
        if ($this->isEmpty()) {
            return null;
        }

        if (!$this->hasRealName()) {
            return $this->getNickname();
        }

        // join first and second together for easier handling
        $firstname = $this->joinNameParts(array(
            $this->getFirst(),
            $this->getSecond()
        ));

        // pattern: lastname, firstname
        // if one of them isn't present, the comma is unnecessary
        $name = $this->joinNameParts(array(
            $this->getLast(),
            $firstname
        ), "," . self::NO_BREAK_SPACE);

        // finally, wrap it with prefix and suffix
        return $this->joinNameParts(array(
            $this->getPrefix(),
            $name,
            $this->getSuffix()
        ));
    }

    /**
     * Returns the nickname if present. If not, it will generate the normal name representation.
     *
     * @JMS\VirtualProperty()
     *
     * @return null|string
     * @see getComplete::getFull
     * @see Name::hasNickname
     */
    public function getPersonal()
    {
        if (!$this->hasNickname()) {
            return $this->getComplete();
        }

        return $this->getNickname();
    }

    /**
     * Joins names together.
     * all parts are joined using a no-break-space by default
     * this helps prevent layout issues in the application
     *
     * @param array $parts
     * @param string $glue
     * @return null|string
     */
    protected function joinNameParts(array $parts, $glue = self::NO_BREAK_SPACE)
    {
        // only parts that contain at least one character are valid.
        // strlen(null) luckily returns int(0) so this is an easy check.
        $parts = array_filter($parts, 'strlen');

        if (empty($parts)) {
            return null;
        }

        return implode($glue, $parts);
    }

    /**
     * @return null|string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @return null|string
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * @param null|string $first
     */
    public function setFirst($first)
    {
        $this->first = $first;
    }

    /**
     * @return null|string
     */
    public function getSecond()
    {
        return $this->second;
    }

    /**
     * @return null|string
     */
    public function getLast()
    {
        return $this->last;
    }

    /**
     * @return null|string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * @param null|string $suffix
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;
    }

    /**
     * @return null|string
     */
    public function getNickname()
    {
        return $this->nickname;
    }
}