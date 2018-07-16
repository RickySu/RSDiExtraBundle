<?php
namespace RS\DiExtraBundle\Converter;

use Doctrine\Common\Annotations\Reader;
use RS\DiExtraBundle\Converter\Parser\ClassParser;
use RS\DiExtraBundle\Generator\Factory\ControllerGenerator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Definition;

class DefinitionConverter
{
    /** @var Reader */
    protected $reader;

    /** @var string */
    protected $environment;

    /** @var string */
    protected $cacheDir;

    public function inject(Reader $reader, $environment, $cacheDir)
    {
        $this->reader = $reader;
        $this->environment = $environment;
        $this->cacheDir = $cacheDir;
    }

    /**
     * @param $classFile
     * @return Definition[]
     */
    public function convert($classFile)
    {
        $definitions = array();
        $classMeta = $this->parseClassFile($classFile);
        $this->convertFactoryService($classMeta);

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

        if(!$this->isEnabledInEnvironment($classMeta->environments)){
            return null;
        }

        if($classMeta->parent){
            $definition = new ChildDefinition($classMeta->parent);
        }
        else {
            $definition = new Definition($classMeta->class);
            $definition
                ->setAutoconfigured($classMeta->autoconfigured);
        }

        if($classMeta->controllerProperties){
            $this->createControllerFactory($classMeta, $definition);
            return $definition;
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
            ->setAutowired($classMeta->autowire)
            ->setArguments($classMeta->arguments)
            ->setLazy($classMeta->lazy)
            ->setProperties($classMeta->properties)
            ->setDecoratedService($classMeta->decorates, $classMeta->decorationInnerName, $classMeta->decorationPriority)
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

    protected function isEnabledInEnvironment(array $environments)
    {
        if(count($environments) == 0){
            return true;
        }

        return in_array($this->environment, $environments);
    }

    /**
     * @param $classFile
     * @return ClassMeta
     */
    protected function parseClassFile($classFile)
    {
        $classMeta = new ClassMeta();
        $reflectionClass = $this->getReflectionClass($classFile);
        $classParser = new ClassParser($this->reader, $reflectionClass);
        $classParser->parse($classMeta);
        return $classMeta;
    }

    protected function createControllerFactory(ClassMeta $classMeta, Definition $definition)
    {
        $outputPath = $this->cacheDir."/controllers";
        @mkdir($outputPath, 0777, true);
        $generator = new ControllerGenerator($classMeta->class, array_keys($classMeta->controllerProperties));
        $filePath = "$outputPath/{$generator->getFactoryClassName()}.php";
        file_put_contents($filePath, $generator->getDefine());
        include_once $filePath;
        $definition
            ->setFile($filePath)
            ->setFactory(array($generator->getFactoryClassFullName(), 'create'))
            ->setArguments(array_values($classMeta->controllerProperties))
            ->setPublic(true);
    }

    protected function convertFactoryService(ClassMeta $classMeta)
    {
        if(!$classMeta->nextClassMeta){
            return;
        }

        $this->convertFactoryServiceInject($classMeta);
    }

    /**
     * @param ClassMeta $classMeta
     */
    protected function convertFactoryServiceInject(ClassMeta $classMeta): void
    {
        if(!$classMeta->methodCalls){
           return;
        }

        $methodCalls = array();

        foreach ($classMeta->methodCalls as $methodCall) {
            list($methodName, $arguments) = $methodCall;

            $factoryClassMeta = $classMeta->nextClassMeta;

            /** @var ClassMeta $factoryClassMeta */
            while ($factoryClassMeta) {
                list($factoryClassName, $factoryMethodName) = $factoryClassMeta->factoryMethod;
                if ($factoryMethodName == $methodName) {
                    $factoryClassMeta->arguments = $arguments;
                    continue 2;
                }

                $factoryClassMeta = $factoryClassMeta->nextClassMeta;
            }

            $methodCalls[] = $methodCall;
        }

        $classMeta->methodCalls = $methodCalls;
    }

}
