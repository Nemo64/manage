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
     * @Route("contacts/", name="contacts")
     */
    public function contactsAction()
    {
        $response = $this->render('AppBundle:Manage:contacts.html.twig');
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
