<?php
namespace RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Controller;

use RS\DiExtraBundle\Annotation\Inject;
use RS\DiExtraBundle\Annotation\InjectParams;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/foo")]
class FooController extends AbstractController
{
    use FooTrait;

    /**
     * @Inject()
     */
    protected $fooStaticFactory;

    #[Inject]
    protected $fooStaticAttributeFactory;

    /**
     * @Inject("foo_static_factory2")
     */
    protected $fooStaticFactory2;

    #[Inject("foo_static_attribute_factory2")]
    protected $fooStaticAttributeFactory2;

    public $injectParams;
    public $injectParams1;
    public $constructParams;

    /**
     * FooController constructor.
     * @param $foo
     * @param $fooStaticFactory2
     * @param $fooStaticFactory3
     * @InjectParams({
     *     "foo" = @Inject("%foo%"),
     * })
     */
    public function __construct($foo, $fooStaticFactory2, $fooStaticFactory3)
    {
        $this->constructParams = array(
            'fooStaticFactory2' => $fooStaticFactory2,
            'fooStaticFactory3' => $fooStaticFactory3,
            'foo' => $foo,
        );
    }

    #[InjectParams]
    #[Inject(name: 'foo', value: '%foo%')]
    public function injectParams($fooStaticFactory3, $foo)
    {
        $this->injectParams = array(
            'fooStaticFactory3' => $fooStaticFactory3,
            'foo' => $foo,
        );
    }

    /**
     * @param $fooStaticFactory3
     * @param $foo
     * @InjectParams({
     *     "foo" = @Inject("%foo%"),
     * })
     */
    public function injectParams1($fooStaticFactory, $fooStaticFactory2, $fooStaticFactory3, $foo)
    {
        $this->injectParams1 = array(
            'fooStaticFactory' => $fooStaticFactory,
            'fooStaticFactory2' => $fooStaticFactory2,
            'fooStaticFactory3' => $fooStaticFactory3,
            'foo' => $foo,
        );
    }

    #[Route("/bar")]
    public function barAction(Request $request)
    {
        return new Response(json_encode(array(
            'fooStaticFactory' => $this->fooStaticFactory,
            'fooStaticFactory2' => $this->fooStaticFactory2,
            'fooFromTrait' => $this->fooFromTrait,
            'injectParams' => $this->injectParams,
            'injectParams1' => $this->injectParams1,
            'constructParams' => $this->constructParams,
        ), true));
    }
}
