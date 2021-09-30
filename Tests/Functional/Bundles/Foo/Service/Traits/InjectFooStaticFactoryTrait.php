<?php
namespace RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Service\Traits;


use RS\DiExtraBundle\Annotation\InjectParams;

trait InjectFooStaticFactoryTrait
{
    public $injectFooStaticFactoryCounter = 0;
    public $fooStaticFactory;

    /**
     * @InjectParams()
     */
    public function injectFooStaticFactory($fooStaticFactory)
    {
        $this->fooStaticFactory = $fooStaticFactory;
        $this->injectFooStaticFactoryCounter++;
    }
}