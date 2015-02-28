<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 28.02.15
 * Time: 18:07
 */

namespace CompanyBundle\Controller;


use CompanyBundle\Entity\Company;
use FOS\RestBundle\Controller\FOSRestController;

class CompanyController extends FOSRestController
{
    public function getCompanyAction(Company $company)
    {
        $view = $this->view($company);
        $response = $this->handleView($view);
        return $response;
    }
}