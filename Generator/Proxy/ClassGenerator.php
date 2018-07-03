<?php
namespace RS\DiExtraBundle\Generator\Proxy;

class ClassGenerator extends BaseGenerator
{
    const IDENT = '    ';

    /** @var \ReflectionClass  */
    protected $reflectionClass;
    /** @var MethodGenerator[] */
    protected $methods;
    protected $traits = array();

    public function __construct(\ReflectionClass $reflectionClass)
    {
        $this->reflectionClass = $reflectionClass;
        $this->generateMethods();
    }

    protected function generateMethods()
    {
        /** @var \ReflectionMethod[] $reflectionMethods */
        $reflectionMethods = array_reverse($this->getReflectionMethods($this->reflectionClass));
        foreach ($reflectionMethods as $reflectionMethod){

            if(strpos($reflectionMethod->getName(), '__') === 0){
                continue;
            }

            if($reflectionMethod->isAbstract()){
                continue;
            }

            if(!$reflectionMethod->isPublic()){
                continue;
            }
            $this->addMethod($reflectionMethod);
        }
    }

    public function addTrait($traitClass)
    {
        $this->addUse($traitClass);
        $this->traits[$traitClass] = true;
    }

    public function getDefinition($namespace, $class)
    {
        return <<<EOT
<?php
namespace $namespace;

{$this->getUsesDefine()}
class $class
{
{$this->getTraitsDefine()}{$this->getMethodsDefine()}
}
EOT;
    }

    protected function getReflectionMethods(\ReflectionClass $reflectionClass)
    {
        $reflectionMethods = $reflectionClass->getMethods();

        foreach ($this->reflectionClass->getTraits() as $traitReflectionClass){
            $reflectionMethods = array_merge($reflectionMethods, $traitReflectionClass->getMethods());
        }

        if(!$reflectionClass->getParentClass()){
            return $reflectionMethods;
        }

        return array_merge($reflectionMethods, $this->getReflectionMethods($reflectionClass->getParentClass()));
    }

    protected function addMethod(\ReflectionMethod $reflectionMethod)
    {
        $this->methods[$reflectionMethod->getName()] = new MethodGenerator($reflectionMethod, $this);
    }

    protected function getMethodsDefine()
    {
        $define = '';
        foreach ($this->methods as $method){
            $define .= $method->getDefinition()."\n";
        }
        return $define;
    }

    public function generate()
    {
        foreach ($this->methods as $method){
            $method->generate();
        }
    }

    protected function getUsesDefine()
    {
        $usesDefine = '';
        foreach ($this->uses as $use => $alias){
            $usesDefine .= "use {$use}".($alias == ''?'':"as $alias").";\n";
        }
        return $usesDefine;
    }

    public function addUse($fullName, $alias = '')
    {
        $this->uses[$fullName] = $alias;
    }

    protected function getTraitsDefine()
    {
        $traitsDefine = '';
        foreach ($this->traits as $trait => $nothing){
            $shortName = $this->getShortName($trait);
            $traitsDefine .= "{$this->getIdent()}use {$shortName};\n";
        }
        return $traitsDefine;
    }
}