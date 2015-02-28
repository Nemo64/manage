<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("manage/")
 */
class ManageController extends Controller
{
    /**
     * @Route("people/", name="people")
     */
    public function contactsAction()
    {
        $response = $this->render('AppBundle:Manage:people.html.twig');
        $response->setSharedMaxAge(60);
        return $response;
    }

    /**
     * @Route("companies/", name="companies")
     */
    public function companiesAction()
    {
        $response = $this->render('AppBundle:Manage:companies.html.twig');
        $response->setSharedMaxAge(60);
        return $response;
    }
}
