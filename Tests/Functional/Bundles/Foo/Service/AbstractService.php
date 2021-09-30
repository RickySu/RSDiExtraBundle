<?php
namespace RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Service;


use RS\DiExtraBundle\Annotation\Inject;
use RS\DiExtraBundle\Annotation\InjectParams;

abstract class AbstractService
{
    use Traits\InjectFooStaticFactoryTrait;

    public $fooPublicService;
    public $foo;
    public $foo2;
    public $foo2Origin;

    /**
     * @InjectParams()
     */
    public function injectFooPublic(FooPublicService $fooPublicService)
    {
        $this->fooPublicService = $fooPublicService;
    }

    public function injectFooService($fooPublicService)
    {
        $this->foo = $fooPublicService;
    }

    /**
     * @param $fooNotPublicService
     * @InjectParams({
     *     "fooNotPublicService" = @Inject("foo_not_public")
     * })
     */
    public function injectFoo2Service($fooNotPublicService)
    {
        $this->foo2Origin = $fooNotPublicService;
    }

}