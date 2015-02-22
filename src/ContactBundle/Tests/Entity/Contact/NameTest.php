<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 15.02.15
 * Time: 12:14
 */
namespace ContactBundle\Tests\Entity\Contact;

use ContactBundle\Entity\Contact;

class NameTest extends \PHPUnit_Framework_TestCase
{
    public function testNormalRepresentation()
    {
        static $nbsp = Contact\Name::NO_BREAK_SPACE;

        $firstAndLastName = new Contact\Name("Mustermann", "Max");
        $this->assertEquals("Max${nbsp}Mustermann", $firstAndLastName->getNormal());

        $firstnameOnly = new Contact\Name(null, "Max");
        $this->assertEquals("Max", $firstnameOnly->getNormal());

        $lastnameOnly = new Contact\Name("Mustermann");
        $this->assertEquals("Mustermann", $lastnameOnly->getNormal());

        $fullname = new Contact\Name("Mustermann", "Max", "Peter", "Maxi", "Dr.", "-suffix");
        $this->assertEquals("Dr.${nbsp}Max${nbsp}Peter${nbsp}Mustermann${nbsp}-suffix", $fullname->getNormal());
    }

    public function testProfessionalRepresentation()
    {
        static $nbsp = Contact\Name::NO_BREAK_SPACE;

        $firstAndLastName = new Contact\Name("Mustermann", "Max");
        $this->assertEquals("Mustermann,${nbsp}Max", $firstAndLastName->getProfessional());

        $firstnameOnly = new Contact\Name(null, "Max");
        $this->assertEquals("Max", $firstnameOnly->getProfessional());

        $lastnameOnly = new Contact\Name("Mustermann");
        $this->assertEquals("Mustermann", $lastnameOnly->getProfessional());

        $fullname = new Contact\Name("Mustermann", "Max", "Peter", "Maxi", "Dr.", "-suffix");
        $this->assertEquals("Dr.${nbsp}Mustermann,${nbsp}Max${nbsp}Peter${nbsp}-suffix", $fullname->getProfessional());
    }

    public function testPersonal()
    {
        static $nbsp = Contact\Name::NO_BREAK_SPACE;

        $complete = new Contact\Name("Mustermann", "Max", null, "Maxi");
        $this->assertTrue($complete->hasNickname());
        $this->assertTrue($complete->hasRealName());
        $this->assertEquals("Maxi", $complete->getPersonal());

        $onlyNickname = new Contact\Name(null, null, null, "Maxi");
        $this->assertTrue($onlyNickname->hasNickname());
        $this->assertFalse($onlyNickname->hasRealName());
        $this->assertEquals("Maxi", $onlyNickname->getPersonal());

        $onlyRealName = new Contact\Name("Mustermann", "Max");
        $this->assertFalse($onlyRealName->hasNickname());
        $this->assertTrue($onlyRealName->hasRealName());
        $this->assertEquals("Max${nbsp}Mustermann", $onlyRealName->getPersonal());
    }
}