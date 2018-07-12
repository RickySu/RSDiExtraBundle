<?php
namespace RS\DiExtraBundle\Tests\Funtional;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use RS\DiExtraBundle\RSDiExtraBundle;
use RS\DiExtraBundle\Tests\Funtional\Bundles\Bar\BarBundle;
use RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\FooBundle;
use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{

    public function __construct(string $environment, bool $debug)
    {
        parent::__construct($environment, $debug);
        $this->config = __DIR__."/config/config.yaml";
    }

    /**
     * Returns an array of bundles to register.
     *
     * @return iterable|BundleInterface[] An iterable of bundle instances
     */
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new DoctrineBundle(),
            new RSDiExtraBundle(),
            new SensioFrameworkExtraBundle(),
            new FooBundle(),
            new BarBundle(),
        ];
    }

    /**
     * Loads the container configuration.
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->config);
    }

    public function getCacheDir()
    {
        return sys_get_temp_dir().'/RSDiExtraBundle/'.substr(sha1($this->config), 0, 6);
    }

}
