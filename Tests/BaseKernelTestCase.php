<?php
namespace RS\DiExtraBundle\Tests;

use RS\DiExtraBundle\Tests\Functional\AppKernel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Kernel;

abstract class BaseKernelTestCase extends BaseTestCase
{
    /** @var Kernel */
    protected $kernel;

    /** @var ContainerInterface */
    protected $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootKernel();
    }

    protected function tearDown(): void
    {
        $this->kernel->shutdown();
        unset($this->kernel);
        unset($this->container);
        parent::tearDown();
    }

    protected function bootKernel()
    {
        $this->kernel = new AppKernel('test', true);
        $this->kernel->boot();
        $this->container = $this->kernel->getContainer()->get('test.service_container');
    }
}
