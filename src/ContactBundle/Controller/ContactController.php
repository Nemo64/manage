<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 15.02.15
 * Time: 12:12
 */
namespace ContactBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;

class ContactController extends FOSRestController
{
    public function getContactsAction()
    {
        $contacts = $this->getDoctrine()->getRepository('ContactBundle:Contact')->findAllSortedByName();

        $view = $this->view($contacts);

        $response = $this->handleView($view);
        $response->setSharedMaxAge(10);
        return $response;
    }
}