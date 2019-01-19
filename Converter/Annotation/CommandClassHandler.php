<?php
namespace RS\DiExtraBundle\Converter\Annotation;

use RS\DiExtraBundle\Annotation\Command;
use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Annotation\Tag;
use RS\DiExtraBundle\Converter\ClassMeta;

class CommandClassHandler
{
    public function handle(ClassMeta $classMeta, \ReflectionClass $reflectionClass, Command $commandAnnotation)
    {
        if($classMeta->id === null) {
            $serviceAnnotation = new Service();
            $serviceAnnotation->public = false;
            (new ServiceClassHandler())->handle($classMeta, $reflectionClass, $serviceAnnotation);
        }

        $tagAnnotation = new Tag();
        $tagAnnotation->name = 'console.command';
        if($commandAnnotation->command !== null) {
            $tagAnnotation->attributes = array(
                'command' => $commandAnnotation->command,
            );
        }
        (new TagClassHandler())->handle($classMeta, $reflectionClass, $tagAnnotation);
    }
}
