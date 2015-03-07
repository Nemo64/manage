<?php

namespace Nemo64\EntityExtraBundle;

use Nemo64\EntityExtraBundle\CompilerPass\EntityFieldLogicCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Nemo64EntityExtraBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new EntityFieldLogicCompilerPass());
    }
}
