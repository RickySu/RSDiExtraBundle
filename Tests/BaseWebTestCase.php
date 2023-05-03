<?php
namespace RS\DiExtraBundle\Tests;

use RS\DiExtraBundle\Tests\Functional\AppKernel;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class BaseWebTestCase extends WebTestCase
{
    /** @var KernelBrowser */
    protected $client;

    /** @var Router */
    protected $router;

    use BaseTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->router = $this->client->getContainer()->get('router');
    }

    protected static function createKernel(array $options = array())
    {
        return new AppKernel(
            isset($options['config']) ? $options['config'] : 'test',
            isset($options['debug']) ? (bool) $options['debug'] : false
        );
    }
}
