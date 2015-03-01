<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 01.03.15
 * Time: 15:47
 */

namespace Nemo64\RestHelperBundle\Service;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

interface RestHelperInterface
{
    /**
     * @param object $object
     * @param Request $request
     * @return object
     * @throws HttpException
     */
    public function processPut($object, Request $request);

    /**
     * @param string $className
     * @param Request $request
     * @return object
     * @throws HttpException
     */
    public function processPost($className, Request $request);

    /**
     * @param object $object
     * @param bool $flush
     * @return Response
     */
    public function validateAndCreateResponse($object, $flush = false);
}