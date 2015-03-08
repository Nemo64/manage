<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 08.03.15
 * Time: 00:45
 */

namespace PersonBundle\Controller\Person;


use FOS\RestBundle\Controller\FOSRestController;
use PersonBundle\Entity\Person\Employment;

class EmploymentController extends FOSRestController
{
    public function getEmploymentPersonAction(Employment $employment)
    {
        $person = $employment->getPerson();
        return $this->view($person);
    }

    public function getEmploymentCompanyAction(Employment $employment)
    {
        $company = $employment->getCompany();
        return $this->view($company);
    }
}