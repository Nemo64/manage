<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 15.02.15
 * Time: 12:12
 */
namespace PersonBundle\Controller;


use PersonBundle\Entity\Person;
use FOS\RestBundle\Controller\FOSRestController;

class PersonController extends FOSRestController
{
    public function getPeopleAction()
    {
        $persons = $this->getDoctrine()->getRepository('PersonBundle:Person')->findAll();

        $view = $this->view($persons);
        $response = $this->handleView($view);
        $response->setSharedMaxAge(30);
        return $response;
    }

    public function getPersonAction(Person $person)
    {
        $view = $this->view($person);
        $response = $this->handleView($view);
        $response->setMaxAge(30);
        return $response;
    }

    public function getPersonEmployedAction(Person $person)
    {
        $view = $this->view($person->getEmployedByCompanies());
        return $this->handleView($view);
    }
}