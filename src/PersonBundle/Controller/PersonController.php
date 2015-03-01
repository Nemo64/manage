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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class PersonController extends FOSRestController
{
    /**
     * @return Response
     */
    public function getPeopleAction()
    {
        $persons = $this->getDoctrine()->getRepository('PersonBundle:Person')->findAll();

        return $this->view($persons);
    }

    /**
     * @param Person $person
     * @return Response
     */
    public function getPersonAction(Person $person)
    {
        return $this->view($person);
    }

    /**
     * @param Person $person
     * @param Request $request
     * @return Response
     */
    public function putPersonAction(Person $person, Request $request)
    {
        $this->get('rest_helper')->processPut($person, $request);
        return $this->get('rest_helper')->validateAndCreateResponse($person, true);
    }

    /**
     * @param Person $person
     * @return Response
     */
    public function deletePersonAction(Person $person)
    {
        $this->getDoctrine()->getManager()->remove($person);
        return $this->get('rest_helper')->validateAndCreateResponse($person, true);
    }

    /**
     * @param Person $person
     * @return Response
     */
    public function getPersonEmployedAction(Person $person)
    {
        $companies = $person->getEmployedByCompanies();

        return $this->view($companies);
    }
}