<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 01.03.15
 * Time: 15:46
 */

namespace Nemo64\RestHelperBundle\Service;


use Doctrine\Common\Persistence\ObjectManager;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\Exception\ValidationFailedException;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JmsSerializerRestHelper implements RestHelperInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, ObjectManager $manager)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->manager = $manager;
    }

    /**
     * @param object $object
     * @param Request $request
     * @param DeserializationContext $context
     * @return Response
     * @throws HttpException
     * @throws ValidationFailedException
     */
    public function processPut($object, Request $request, DeserializationContext $context = null)
    {
        if (!is_object($object)) {
            $msg = "Object given is '" . gettype($object) . "! Assume it wasn't found.";
            throw new NotFoundHttpException($msg);
        }

        $this->validateQuery($request);

        $className = get_class($object);
        $requestBody = json_decode($request->getContent(), true);

        $metaClass = $this->manager->getClassMetadata($className);
        $identifier = $metaClass->getIdentifierValues($object);

        // the identifier need to be in the json string so the doctrine_object_constructor can identify the entity
        $json = json_encode(array_merge($requestBody, $identifier));

        // finally deserialize the object
        // the jms_serializer.object_constructor must be jms_serializer.doctrine_object_constructor for this to work
        $resultObject = $this->serializer->deserialize($json, $className, 'json', $context);
        if ($resultObject !== $object) {
            $msg = "The object wasn't the same after deserialize. This shouldn't happen.\n";
            $msg .= "Make sure 'jms_serializer.object_constructor' is 'jms_serializer.doctrine_object_constructor'.";
            throw new NotFoundHttpException($msg);
        }

        return $resultObject;
    }

    /**
     * @param string $className
     * @param Request $request
     * @param DeserializationContext $context
     * @return object
     */
    public function processPost($className, Request $request, DeserializationContext $context = null)
    {
        $this->validateQuery($request);

        $json = $request->getContent();
        $resultObject = $this->serializer->deserialize($json, $className, 'json', $context);

        return $resultObject;
    }

    /**
     * @param object $object
     * @param bool $flush
     * @return Response
     */
    public function validateAndCreateResponse($object, $flush = false)
    {
        $violations = $this->validator->validate($object);
        if (count($violations) > 0) {
            throw new ValidationFailedException($violations);
        }

        if ($flush) {
            $this->manager->flush();
        }

        return new Response('', Response::HTTP_ACCEPTED);
    }

    /**
     * @param Request $request
     */
    protected function validateQuery(Request $request)
    {
        $format = $request->get('_format');
        if ($format !== 'json') {
            $msg = "format '$format' is not supported";
            throw new HttpException(Response::HTTP_NOT_ACCEPTABLE, $msg);
        }

        $bodyContentType = $request->headers->get('content-type');
        if ($bodyContentType !== 'application/json') {
            $msg = "body format must be application/json, got '$bodyContentType'";
            throw new HttpException(Response::HTTP_NOT_ACCEPTABLE, $msg);
        }
    }
}