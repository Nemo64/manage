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

class EntityFieldLogicCompilerPass implements CompilerPassInterface
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
                $this->processCreateTimeField($entityReflectionClass, $container);
            }
        }
    }

    /**
     * @param \ReflectionClass $entityReflectionClass
     * @param ContainerBuilder $container
     */
    protected function processCreateTimeField(\ReflectionClass $entityReflectionClass, ContainerBuilder $container)
    {
        $annotationReader = $container->get('annotation_reader');
        $entityCreateTimeListenerDefinition = $container->getDefinition('nemo64_entity_extra.create_time_listener');

        foreach ($entityReflectionClass->getProperties() as $property) {

            static $annotationName = 'Nemo64\EntityExtraBundle\Annotation\CreateTime';
            $createTimeAnnotation = $annotationReader->getPropertyAnnotation($property, $annotationName);

            if ($createTimeAnnotation === null) {
                continue;
            }

            $arguments = array($entityReflectionClass->name, $property->name);
            $entityCreateTimeListenerDefinition->addMethodCall('addCreateTimeProperty', $arguments);
        }
    }
}