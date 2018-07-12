<?php
namespace RS\DiExtraBundle\Generator\Factory;


class ControllerGenerator
{
    const IDENT = '    ';
    protected $className;
    /** @var array */
    protected $parameters;

    public function __construct($className, $parameters)
    {
        $this->className = $className;
        $this->parameters = $parameters;
    }

    protected function getFactoryNamespace()
    {
        return 'RS\\DiExtraBundle\\Factory\Controller';
    }

    public function getFactoryClassName()
    {
        return str_replace('\\', '_', $this->className);
    }

    public function getFactoryClassFullName()
    {
        return $this->getFactoryNamespace().'\\'.$this->getFactoryClassName();
    }

    public function getDefine()
    {
        return <<<EOT
<?php
namespace {$this->getFactoryNamespace()};

class {$this->getFactoryClassName()}
{
    public static function factory({$this->getInjectParameters()})
    {
        \$controller = new \\{$this->className}();
{$this->getSetterDefine()}
        return \$controller;
    }
    
    protected static function setProperty(\$object, \$reflectionClass, \$propertyName, \$property)
    {
        \$reflectionProperty = \$reflectionClass->getProperty(\$propertyName);
        \$reflectionProperty->setAccessible(true);
        \$reflectionProperty->setValue(\$object, \$property);
    }
}
EOT;
    }

    protected function getInjectParameters()
    {
        $parameters = array();
        foreach ($this->parameters as $parameter){
            $parameters[] = "\$$parameter";
        }
        return implode(', ', $parameters);
    }

    protected function getSetterDefine()
    {
        if(count($this->parameters) == 0){
            return;
        }

        $injects = array();

        foreach($this->parameters as $parameter){
            $injects[] = "{$this->getIdent(2)}self::setProperty(\$controller, \$reflectClass, '$parameter', \$$parameter);";
        }

        return "{$this->getIdent(2)}\$reflectClass = new \ReflectionClass(\$controller);\n".implode("\n", $injects);
    }

    protected function getIdent($tab = 1)
    {
        return str_repeat(self::IDENT, $tab);
    }
}