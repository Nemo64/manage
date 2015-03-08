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
use Symfony\Component\HttpFoundation\Request;

class PersonController extends FOSRestController
{
    public function getPeopleAction()
    {
        $persons = $this->getDoctrine()->getRepository('PersonBundle:Person')->findAll();
        return $this->view($persons);
    }

    public function getPersonAction(Person $person)
    {
        return $this->view($person);
    }

    public function putPersonAction(Person $person, Request $request)
    {
        $this->get('rest_helper')->processPut($person, $request);
        return $this->get('rest_helper')->validateAndCreateResponse($person, true);
    }

    public function deletePersonAction(Person $person)
    {
        $this->getDoctrine()->getManager()->remove($person);
        return $this->get('rest_helper')->validateAndCreateResponse($person, true);
    }

    public function getPersonEmploymentsAction(Person $person)
    {
        $companies = $person->getEmployments();
        return $this->view($companies);
    }
}