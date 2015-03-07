<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 07.03.15
 * Time: 17:38
 */

namespace Nemo64\EntityExtraBundle\CompilerPass;


use Symfony\Component\ClassLoader\ClassMapGenerator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EntityLogicCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        foreach ($bundles as $bundle) {
            $bundleReflectionClass = new \ReflectionClass($bundle);

            $bundleDir = dirname($bundleReflectionClass->getFileName());
            $entityDir = "$bundleDir/Entity";

            // we only care for bundles with an entity folder
            if (!is_dir($entityDir)) {
                continue;
            }

            $classMap = ClassMapGenerator::createMap($entityDir);
            foreach ($classMap as $className => $classFile) {

                $entityReflectionClass = new \ReflectionClass($className);
                $this->processPropertyListener($entityReflectionClass, $container);
            }
        }
    }

    /**
     * @param \ReflectionClass $entityReflectionClass
     * @param ContainerBuilder $container
     */
    protected function processPropertyListener(\ReflectionClass $entityReflectionClass, ContainerBuilder $container)
    {
        static $tagName = 'entity_extra.property_listener';

        $annotationReader = $container->get('annotation_reader');

        $serviceTagAttributes = $container->findTaggedServiceIds($tagName);
        foreach ($serviceTagAttributes as $serviceId => $tags) {
            $serviceDefinition = $container->getDefinition($serviceId);

            foreach ($tags as $attributes) {

                if (!isset($attributes['annotation'])) {
                    throw new \LogicException("The tag '$tagName' requires a 'annotation' attribute.");
                }

                $annotationName = $attributes['annotation'];

                foreach ($entityReflectionClass->getProperties() as $property) {

                    $annotation = $annotationReader->getPropertyAnnotation($property, $annotationName);
                    if ($annotation === null) {
                        continue;
                    }

                    $arguments = array($entityReflectionClass->name, $property->name);
                    $serviceDefinition->addMethodCall('addRelatedProperty', $arguments);
                }
            }
        }
    }
}