<?php
namespace RS\DiExtraBundle\Generator\Proxy;


class ParameterGenerator extends BaseGenerator
{
    /** @var \ReflectionParameter  */
    protected $reflectionParameter;
    protected $parameterType;
    protected $parameterName;
    protected $parameterDefaultValue;
    /** @var MethodGenerator  */
    protected $methodGenerator;

    /**
     * ParameterGenerator constructor.
     * @param \ReflectionParameter $parameter
     */
    public function __construct(\ReflectionParameter $reflectionParameter, MethodGenerator $methodGenerator)
    {
        $this->methodGenerator = $methodGenerator;
        $this->reflectionParameter = $reflectionParameter;
    }

    public function generate()
    {
        $this->parameterType = $this->generateParameterType();
        $this->parameterName = $this->generateParameterName();
        $this->parameterDefaultValue = $this->generateParameterDefaultValue();
    }

    public function getParametersDefine()
    {
        return $this->parameterName;
    }

    public function getDefinition()
    {
        return "{$this->parameterType}{$this->parameterName}{$this->parameterDefaultValue}";
    }

    protected function generateParameterName()
    {
        return "\${$this->reflectionParameter->getName()}";
    }

    protected function generateParameterDefaultValue()
    {
        if(!$this->reflectionParameter->isDefaultValueAvailable()){
            return '';
        }

        if($this->reflectionParameter->isDefaultValueConstant()){
            $constantName = $this->reflectionParameter->getDefaultValueConstantName();

            if($this->isClassConstant($constantName)){
                list($fullClass, $constant) = explode('::', $constantName);
                $this->addUse($fullClass);
                $shortName = $this->getShortName($fullClass);
                return " = $shortName::$constant";

            }

            $shortName = $this->getShortName($constantName);
            return " = $shortName";
        }

        return " = {$this->convertDefaultValue($this->reflectionParameter->getDefaultValue())}";
    }

    protected function convertDefaultValue($value)
    {
        if(is_null($value)){
            return 'null';
        }

        if(is_numeric($value)){
            return $value;
        }

        if(is_bool($value)){
            return $value?'true':'false';
        }

        return "'".addslashes($value)."'";
    }

    protected function generateParameterType()
    {
        if(!$this->reflectionParameter->hasType()){
            return '';
        }

        return $this->generateType($this->reflectionParameter->getType());
    }

    public function addUse($fullName, $alias = '')
    {
        return $this->methodGenerator->addUse($fullName, $alias);
    }

    protected function isClassConstant($constantName)
    {
        return strpos($constantName, '::') !== false;
    }
}
