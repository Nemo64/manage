<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 15.02.15
 * Time: 12:12
 */
namespace ContactBundle\Controller;


use ContactBundle\Entity\Contact;
use FOS\RestBundle\Controller\FOSRestController;

class ContactController extends FOSRestController
{
    public function getContactsAction()
    {
        $contacts = $this->getDoctrine()->getRepository('ContactBundle:Contact')->findAll();

        $view = $this->view($contacts);
        $response = $this->handleView($view);
        $response->setSharedMaxAge(30);
        return $response;
    }

    public function getContactAction(Contact $contact)
    {
        $view = $this->view($contact);
        $response = $this->handleView($view);
        $response->setMaxAge(30);
        return $response;
    }
}