<?php
namespace RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Service\Traits;


use RS\DiExtraBundle\Annotation\InjectParams;

trait InjectFooStaticFactory2Trait
{
    public $fooStaticFactory2;

    /**
     * @InjectParams()
     */
    public function injectFooStaticFactory2($fooStaticFactory2)
    {
        $this->fooStaticFactory2 = $fooStaticFactory2;
    }
}