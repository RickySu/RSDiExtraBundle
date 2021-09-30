<?php
namespace RS\DiExtraBundle\Converter;

class ClassMeta
{
    public $class;
    public $id;
    public $parent;
    public $shared = true;
    public $public = true;
    public $private = false;
    public $decorates;
    public $decorationInnerName;
    public $decorationPriority = 0;
    public $deprecated = false;
    public $abstract = false;
    public $tags = array();
    public $arguments = array();
    public $methodCalls = array();
    public $properties = array();
    public $controllerProperties = array();
    public $environments = array();
    public $autowire = false;
    public $factoryMethod;
    public $synthetic = false;
    public $lazy = false;
    public $autoconfigured = false;
    public $factoryClass;
    public $factoryTags = array();
    public $nextClassMeta;
    public $isController = false;
}
