<?php
namespace RS\DiExtraBundle\Converter;

class ClassMeta
{
    public $class;
    public $id;
    public $parent;
    public $shared;
    public $public = true;
    public $private = false;
    public $deprecated;
    public $abstract;
    public $tags = array();
    public $arguments = array();
    public $methodCalls = array();
    public $lookupMethods = array();
    public $properties = array();
    public $initMethods = array();
    public $environments = array();
    public $autowire;
    public $factoryMethod;
    public $synthetic;
    public $lazy;
    public $autoconfigured;
    public $nextClassMeta;
}