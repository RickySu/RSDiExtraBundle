<?php
namespace RS\DiExtraBundle\Generator\Factory;


class ControllerGenerator
{
    const IDENT = '    ';
    protected $className;

    protected $paramererCount;

    /** @var array */
    protected $propertyParameters;
    /** @var array  */
    protected $constructParameters;
    /** @var array  */
    protected $injectParameters;

    protected $injectParameterStart = 0;

    protected $propertyParameterStart = 0;

    protected $classSuffix = '';

    public function __construct($className, array $constructParameters = array(), array $injectParameters = array(), array $propertyParameters = array())
    {
        $this->className = $className;
        $this->constructParameters = $constructParameters;
        $this->injectParameters = $injectParameters;
        $this->propertyParameters = $propertyParameters;
        $this->initParameters();
        $this->classSuffix = md5(microtime().rand());
    }

    protected function getFactoryNamespace()
    {
        return 'RS\\DiExtraBundle\\Factory\Controller';
    }

    public function getFactoryClassName()
    {
        return str_replace('\\', '_', $this->className)."_{$this->classSuffix}";
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
    public static function create({$this->getParametersDefine(0, $this->paramererCount)})
    {
{$this->getConstructDefine()}
{$this->getInjectMethodDefines()}
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

    protected function getParametersDefine($parametersStart, $count)
    {
        $parameters = array();
        for ($i = $parametersStart; $i < $parametersStart + $count; $i++){
            $parameters[] = "\$p$i";
        }
        return implode(', ', $parameters);
    }

    protected function getSetterDefine()
    {
        if(count($this->propertyParameters) == 0){
            return '';
        }

        $injects = array();

        for ($i = $this->propertyParameterStart; $i < $this->paramererCount; $i++){
            $injects[] = "{$this->getIdent(2)}self::setProperty(\$controller, \$reflectClass, '{$this->propertyParameters[$i - $this->propertyParameterStart]}', \$p$i);";
        }

        return "{$this->getIdent(2)}\$reflectClass = new \ReflectionClass(\$controller);\n".implode("\n", $injects);
    }

    protected function getIdent($tab = 1)
    {
        return str_repeat(self::IDENT, $tab);
    }

    protected function initParameters()
    {
        $this->injectParameterStart = count($this->constructParameters);

        $this->propertyParameterStart  = $this->injectParameterStart;

        foreach ($this->injectParameters as $methodName => $parameters){
            $this->propertyParameterStart += count($parameters);
        }

        $this->paramererCount = $this->propertyParameterStart + count($this->propertyParameters);
    }

    protected function getConstructDefine()
    {
        $constructParameters = array();

        for($i = 0; $i < count($this->constructParameters); $i++){
            $constructParameters[] = "\$p$i";
        }
        $constructParametersString = implode(', ', $constructParameters);

        return <<<EOT
{$this->getIdent(2)}\$controller = new \\{$this->className}($constructParametersString);
EOT;

    }

    protected function getInjectMethodDefines()
    {
        $parametersStart = $this->injectParameterStart;

        $methods = array();
        foreach ($this->injectParameters as $methodName => $parameters){
            $methods[] = "{$this->getIdent(2)}\$controller->$methodName({$this->getParametersDefine($parametersStart, count($parameters))});";
            $parametersStart += count($parameters);
        }

        return implode("\n", $methods);
    }

}
