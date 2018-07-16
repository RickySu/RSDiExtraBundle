<?php
namespace RS\DiExtraBundle\Tests\Fixtures\Foo\Bar;

class Bar1
{
    protected $foo;
    private $bar;
    public $buz;

    protected $constructParams;
    protected $inject1Params;
    protected $inject2Params;

    public function __construct($foo, $bar, $buz)
    {
        $this->constructParams = array($foo, $bar, $buz);
    }

    public function inject1($foo, $bar, $buz)
    {
        $this->inject1Params = array($foo, $bar, $buz);
    }

    public function inject2($foo, $bar, $buz)
    {
        $this->inject2Params = array($foo, $bar, $buz);
    }
}
