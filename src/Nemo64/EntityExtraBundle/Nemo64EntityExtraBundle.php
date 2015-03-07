<?php

namespace Nemo64\EntityExtraBundle;

use Doctrine\DBAL\Types\Type;
use Nemo64\EntityExtraBundle\CompilerPass\EntityLogicCompilerPass;
use Nemo64\EntityExtraBundle\Type\WeakRelationType;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Nemo64EntityExtraBundle extends Bundle
{
    public function __construct()
    {
        if (!Type::hasType(WeakRelationType::TYPE)) {
            Type::addType(WeakRelationType::TYPE, 'Nemo64\EntityExtraBundle\Type\WeakRelationType');
        }
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new EntityLogicCompilerPass());
    }
}
