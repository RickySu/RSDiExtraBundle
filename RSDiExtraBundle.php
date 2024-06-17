<?php
namespace RS\DiExtraBundle;

use RS\DiExtraBundle\DependencyInjection\Compiler\AnnotationCompilerPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RSDiExtraBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AnnotationCompilerPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 1024);
    }
}
