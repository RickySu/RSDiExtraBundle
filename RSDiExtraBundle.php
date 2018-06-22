<?php
namespace RS\DiExtraBundle;

use RS\DiExtraBundle\DependencyInjection\Compiler\AnnotationCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RSDiExtraBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $config = $container->getCompilerPassConfig();
        $passes = $config->getBeforeOptimizationPasses();
        array_unshift($passes, new AnnotationCompilerPass());
        $config->setBeforeOptimizationPasses($passes);
    }
}
