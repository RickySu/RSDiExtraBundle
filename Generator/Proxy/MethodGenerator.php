<?php
namespace RS\DiExtraBundle\Generator\Proxy;


class MethodGenerator extends BaseGenerator
{
    /** @var \ReflectionMethod  */
    protected $reflectionMethod;

    /** @var ParameterGenerator[] */
    protected $parameters = array();

    protected $proxyMethodDefines = array();
    /** @var ClassGenerator  */
    protected $classGenerator;

    /**
     * MethodGenerator constructor.
     * @param \ReflectionMethod $reflectionMethod
     */
    public function __construct(\ReflectionMethod $reflectionMethod, ClassGenerator $classGenerator)
    {
        $this->reflectionMethod = $reflectionMethod;
        $this->classGenerator = $classGenerator;
        $this->generateParameters();
    }

    protected function generateParameters()
    {
        foreach($this->reflectionMethod->getParameters() as $parameter){
            $this->addParameter($parameter);
        }
    }

    public function generate()
    {
        foreach ($this->parameters as $parameter){
            $parameter->generate();
        }
        $this->generateProxyMethodDefine();
    }

    public function getDefinition()
    {
        return <<<EOT
{$this->getDocCommentDefine()}
{$this->getIdent()}{$this->getStaticDefine()}public function {$this->reflectionMethod->getName()}({$this->getParametersDefine()}){$this->getReturnTypeDefine()}
{$this->getIdent()}{
{$this->getProxyMethodDefine()}
{$this->getIdent()}}

EOT;
    }

    protected function getDocCommentDefine()
    {
        if($this->reflectionMethod->getDocComment() === false){
            return '';
        }
        return "{$this->getIdent()}{$this->reflectionMethod->getDocComment()}";
    }

    protected function addParameter(\ReflectionParameter $param)
    {
        $this->parameters[$param->getName()] = new ParameterGenerator($param, $this);
    }

    protected function getIdent($count = 1)
    {
        return str_repeat(self::IDENT, $count);
    }

    protected function getStaticDefine()
    {
        if($this->reflectionMethod->isStatic()){
            return 'static ';
        }

        return '';
    }

    protected function getParametersDefine()
    {
        $defines = array();
        foreach ($this->parameters as $parameter){
            $defines[] = $parameter->getDefinition();
        }
        return implode(', ', $defines);
    }

    protected function generateProxyMethodDefine()
    {
        $defines = array();
        foreach ($this->parameters as $parameter){
            $defines[] = $parameter->getParametersDefine();
        }

        if($this->reflectionMethod->isStatic()){
            $fullClassName = $this->reflectionMethod->getDeclaringClass()->getName();
            $shortName = $this->getShortName($fullClassName);
            $this->addUse($fullClassName);
            $this->proxyMethodDefines[] = "return $shortName::{$this->reflectionMethod->getName()}(".implode(', ', $defines).");";
            return;
        }

        $this->proxyMethodDefines[] = "return \$this->entityManagerProxy->{$this->reflectionMethod->getName()}(".implode(', ', $defines).");";
    }

    protected function getProxyMethodDefine()
    {
        $defines = '';
        foreach ($this->proxyMethodDefines as $define){
            $defines .= "{$this->getIdent(2)}$define";
        }
        return $defines;
    }

    public function addUse($fullName, $alias = '')
    {
        return $this->classGenerator->addUse($fullName, $alias);
    }

    protected function getReturnTypeDefine()
    {
        if(!$this->reflectionMethod->hasReturnType()){
            return '';
        }

        return " :{$this->generateType($this->reflectionMethod->getReturnType())}";
    }
}
