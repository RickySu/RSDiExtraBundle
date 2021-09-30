<?php
namespace RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Service\Traits;


use RS\DiExtraBundle\Annotation\InjectParams;

trait InjectFooStaticFactory2Trait
{
    public $injectFooStaticFactory2Counter = 0;
    public $fooStaticFactory2;

    /**
     * @InjectParams()
     */
    public function injectFooStaticFactory2($fooStaticFactory2)
    {
        $this->fooStaticFactory2 = $fooStaticFactory2;
        $this->injectFooStaticFactory2Counter++;
    }
}