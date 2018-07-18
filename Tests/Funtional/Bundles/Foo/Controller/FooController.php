<?php
namespace RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Controller;

use RS\DiExtraBundle\Annotation\Inject;
use RS\DiExtraBundle\Annotation\InjectParams;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/foo")
 */
class FooController extends Controller
{
    /**
     * @Inject()
     */
    protected $fooStaticFactory;

    /**
     * @Inject("foo_static_factory2")
     */
    protected $fooStaticFactory2;

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

    /**
     * @param $fooStaticFactory3
     * @param $foo
     * @InjectParams({
     *     "foo" = @Inject("%foo%"),
     * })
     */
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

    /**
     * @Route("/bar")
     */
    public function barAction(Request $request)
    {
        return new Response(json_encode(array(
            'fooStaticFactory' => $this->fooStaticFactory,
            'fooStaticFactory2' => $this->fooStaticFactory2,
            'injectParams' => $this->injectParams,
            'injectParams1' => $this->injectParams1,
            'constructParams' => $this->constructParams,
        ), true));
    }
}
