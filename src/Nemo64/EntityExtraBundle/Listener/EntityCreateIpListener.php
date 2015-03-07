<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 07.03.15
 * Time: 19:08
 */

namespace Nemo64\EntityExtraBundle\Listener;


use Symfony\Component\HttpFoundation\RequestStack;

class EntityCreateIpListener extends AbstractPropertyCreateListener
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Gets the value the property should have while the entity is created.
     * If the value is null it will not be set.
     *
     * @param object $entity
     * @param \ReflectionProperty $property
     * @return mixed|null
     */
    protected function getCreateValue($entity, \ReflectionProperty $property)
    {
        if (php_sapi_name() === 'cli') {
            return array('cli');
        }

        $request = $this->requestStack->getCurrentRequest();

        if ($request === null) {
            // this should never happen but just in case:
            return array('no request');
        }

        return $request->getClientIps();
    }
}