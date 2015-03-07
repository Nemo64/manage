<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 07.03.15
 * Time: 18:32
 */

namespace Nemo64\EntityExtraBundle\Listener;


use Symfony\Component\Stopwatch\Stopwatch;

abstract class AbstractPropertyListener
{
    /**
     * @var string[][]
     */
    private $properties;

    /**
     * @var \ReflectionProperty[][]
     */
    private $reflectionProperties;

    /**
     * @var Stopwatch
     */
    protected $stopwatch;

    /**
     * @param Stopwatch $stopwatch
     */
    public function setStopwatch($stopwatch)
    {
        $this->stopwatch = $stopwatch;
    }

    /**
     * @param string $className
     * @param string $property
     */
    public function addRelatedProperty($className, $property)
    {
        if (!isset($this->properties[$className])) {
            $this->properties[$className] = array();
        }

        $this->properties[$className][] = $property;
    }

    /**
     * @param string $className
     * @return \ReflectionProperty[]
     */
    protected function getRelatedProperties($className)
    {
        if (isset($this->reflectionProperties[$className])) {
            return $this->reflectionProperties[$className];
        }

        if (!isset($this->properties[$className])) {
            return $this->reflectionProperties[$className] = array();
        }

        $reflectionProperties = array();
        foreach ($this->properties[$className] as $property) {

            $reflectionProperty = new \ReflectionProperty($className, $property);
            $reflectionProperty->setAccessible(true);
            $reflectionProperties[] = $reflectionProperty;
        }

        return $this->reflectionProperties[$className] = $reflectionProperties;
    }
}