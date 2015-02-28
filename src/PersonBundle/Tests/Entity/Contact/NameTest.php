<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 15.02.15
 * Time: 12:14
 */
namespace PersonBundle\Tests\Entity\Person;

use PersonBundle\Entity\Person;

class NameTest extends \PHPUnit_Framework_TestCase
{
    public function testCompleteRepresentation()
    {
        static $nbsp = Person\Name::NO_BREAK_SPACE;

        $firstAndLastName = new Person\Name("Mustermann", "Max");
        $this->assertEquals("Max${nbsp}Mustermann", $firstAndLastName->getComplete());

        $firstnameOnly = new Person\Name(null, "Max");
        $this->assertEquals("Max", $firstnameOnly->getComplete());

        $lastnameOnly = new Person\Name("Mustermann");
        $this->assertEquals("Mustermann", $lastnameOnly->getComplete());

        $fullname = new Person\Name("Mustermann", "Max", "Peter", "Maxi", "Dr.", "-suffix");
        $this->assertEquals("Dr.${nbsp}Max${nbsp}Peter${nbsp}Mustermann${nbsp}-suffix", $fullname->getComplete());
    }

    public function testProfessionalRepresentation()
    {
        static $nbsp = Person\Name::NO_BREAK_SPACE;

        $firstAndLastName = new Person\Name("Mustermann", "Max");
        $this->assertEquals("Mustermann,${nbsp}Max", $firstAndLastName->getProfessional());

        $firstnameOnly = new Person\Name(null, "Max");
        $this->assertEquals("Max", $firstnameOnly->getProfessional());

        $lastnameOnly = new Person\Name("Mustermann");
        $this->assertEquals("Mustermann", $lastnameOnly->getProfessional());

        $fullname = new Person\Name("Mustermann", "Max", "Peter", "Maxi", "Dr.", "-suffix");
        $this->assertEquals("Dr.${nbsp}Mustermann,${nbsp}Max${nbsp}Peter${nbsp}-suffix", $fullname->getProfessional());
    }

    public function testPersonal()
    {
        static $nbsp = Person\Name::NO_BREAK_SPACE;

        $complete = new Person\Name("Mustermann", "Max", null, "Maxi");
        $this->assertTrue($complete->hasNickname());
        $this->assertTrue($complete->hasRealName());
        $this->assertEquals("Maxi", $complete->getPersonal());

        $onlyNickname = new Person\Name(null, null, null, "Maxi");
        $this->assertTrue($onlyNickname->hasNickname());
        $this->assertFalse($onlyNickname->hasRealName());
        $this->assertEquals("Maxi", $onlyNickname->getPersonal());

        $onlyRealName = new Person\Name("Mustermann", "Max");
        $this->assertFalse($onlyRealName->hasNickname());
        $this->assertTrue($onlyRealName->hasRealName());
        $this->assertEquals("Max${nbsp}Mustermann", $onlyRealName->getPersonal());
    }
}