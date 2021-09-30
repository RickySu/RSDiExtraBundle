<?php
namespace RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Service;

use RS\DiExtraBundle\Annotation\Inject;
use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Annotation\Service;

/**
 * @Service("extend_service")
 */
class ExtendService extends AbstractService
{
    use Traits\InjectFooStaticFactory2Trait;

    public $fooNotPublicService;

    /**
     * @InjectParams()
     */
    public function injectFooNotPublicService(FooNotPublicService $fooNotPublicService)
    {
        $this->fooNotPublicService = $fooNotPublicService;
    }

    /**
     * @param $fooNotPublicService
     * @InjectParams({
     *     "fooNotPublicService" = @Inject("foo_not_public")
     * })
     */
    public function injectFooService($fooNotPublicService)
    {
        $this->foo = $fooNotPublicService;
    }

    /**
     * @param $fooNotPublicService
     * @InjectParams({
     *     "fooNotPublicService" = @Inject("foo_not_public")
     * })
     */
    public function injectFoo2Service($fooNotPublicService)
    {
        $this->foo2 = $fooNotPublicService;
    }
}
