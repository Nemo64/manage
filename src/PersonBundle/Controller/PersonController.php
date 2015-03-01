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

        $view = $this->view($persons);
        return $this->handleView($view);
    }

    /**
     * @param Person $person
     * @return Response
     */
    public function getPersonAction(Person $person)
    {
        $view = $this->view($person);
        return $this->handleView($view);
    }

    /**
     * @param Person $person
     * @return Response
     */
    public function putPersonAction(Person $person, Request $request)
    {
        $requestBody = json_decode($request->getContent(), true);
        $json = json_encode(array_merge($requestBody, array('id' => $person->getId())));
        $person = $this->get('serializer')->deserialize($json, get_class($person), $request->get('_format'));
        $violations = $this->get('validator')->validate($person);

        if (count($violations) > 0) {
            $view = $this->view($violations, Response::HTTP_BAD_REQUEST);
            return $this->handleView($view);
        }

        $this->getDoctrine()->getManager()->flush();
        return new Response('', Response::HTTP_ACCEPTED);
    }

    /**
     * @param Person $person
     * @return Response
     */
    public function getPersonEmployedAction(Person $person)
    {
        $view = $this->view($person->getEmployedByCompanies());
        return $this->handleView($view);
    }
}