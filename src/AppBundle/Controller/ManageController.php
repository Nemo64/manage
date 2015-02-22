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
     * @Route("", name="homepage")
     */
    public function defaultAction()
    {
        $response = $this->render('AppBundle:Manage:default.html.twig');
        $response->setMaxAge(60);
        return $response;
    }
}
