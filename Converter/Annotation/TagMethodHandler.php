<?php
namespace RS\DiExtraBundle\Converter\Annotation;

use RS\DiExtraBundle\Annotation\Tag;
use RS\DiExtraBundle\Converter\ClassMeta;

class TagMethodHandler
{
    /**
     * @param ClassMeta $classMeta
     * @param \ReflectionClass $reflector
     * @param Tag $annotation
     */
    public function handle(ClassMeta $classMeta, \ReflectionMethod $reflector, Tag $annotation)
    {

    }
}