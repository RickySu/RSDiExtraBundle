<?php
namespace RS\DiExtraBundle\Converter;

use Doctrine\Common\Annotations\AnnotationReader;
use RS\DiExtraBundle\Converter\Parser\ClassParser;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Definition;

class DefinitionConverter
{
    /** @var AnnotationReader */
    protected $reader;

    public function injectAnnotationReader(AnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param $classFile
     * @return Definition[]
     */
    public function convert($classFile)
    {
        $classMeta = new ClassMeta();
        $reflectionClass = $this->getReflectionClass($classFile);
        $classParser = new ClassParser($this->reader, $reflectionClass);
        $classParser->parse($classMeta);

        $definitions = array();

        while($classMeta) {
            if($definition = $this->convertDefinition($classMeta)){
                $definitions[$classMeta->id] = $definition;
            }
            $classMeta = $classMeta->nextClassMeta;
        }

        return $definitions;
    }

    protected function convertDefinition(ClassMeta $classMeta)
    {
        if($classMeta->id === null){
            return null;
        }

        if($classMeta->parent){
            $definition = new ChildDefinition($classMeta->parent);
        }
        else {
            $definition = new Definition($classMeta->class);
        }

        return $definition
            ->setPrivate($classMeta->private)
            ->setPublic($classMeta->public)
            ->setShared($classMeta->shared)
            ->setAbstract($classMeta->abstract)
            ->setDeprecated($classMeta->deprecated)
            ->setSynthetic($classMeta->synthetic)
            ->setTags($classMeta->tags)
            ->setFactory($classMeta->factoryMethod)
            ->setMethodCalls($classMeta->methodCalls)
            ->setDeprecated($classMeta->deprecated)
            ->setAutowired($classMeta->autowire)
            ->setArguments($classMeta->arguments)
            ->setLazy($classMeta->lazy)
            ;
    }

    protected function getReflectionClass($classFile)
    {
        $className = $this->getClassName($classFile);
        require_once $classFile;
        return new \ReflectionClass($className);
    }

    protected function getClassName($classFile)
    {
        $src = file_get_contents($classFile);
        if (!preg_match('/\bnamespace\s+([^;\{\s]+)\s*?[;\{]/s', $src, $match)) {
            throw new \RuntimeException(sprintf('Namespace could not be determined for file "%s".', $classFile));
        }
        $namespace = $match[1];

        if (!preg_match('/\b(?:class|trait)\s+([^\s]+)\s+(?:extends|implements|{)/is', $src, $match)) {
            throw new \RuntimeException(sprintf('Could not extract class name from file "%s".', $classFile));
        }

        return $namespace.'\\'.$match[1];
    }
}
