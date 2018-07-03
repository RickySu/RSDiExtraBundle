<?php
namespace RS\DiExtraBundle\Generator\Proxy;


abstract class BaseGenerator
{
    const IDENT = '    ';

    protected $uses = array();

    protected function getIdent($count = 1)
    {
        return str_repeat(self::IDENT, $count);
    }

    public function getShortName($fullName)
    {
        $fields = explode('\\', $fullName);
        return  array_pop($fields);
    }

    protected function generateType(\ReflectionType $type)
    {
        $fullName = $type->getName();
        $allowNull = $type->allowsNull()?'?':'';
        if($type->isBuiltin()){
            return "$allowNull$fullName ";
        }
        $this->addUse($fullName);
        $shortName = $this->getShortName($fullName);

        return "$allowNull$shortName ";
    }

    abstract public function addUse($fullName, $alias = '');
}
