<?php
namespace RS\DiExtraBundle\Tests;

use RS\DiExtraBundle\Tests\Functional\AppKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class BaseKernelTestCase extends KernelTestCase
{
    use BaseTestTrait;

    protected static function createKernel(array $options = array())
    {
        return new AppKernel(
            isset($options['environment']) ? $options['environment'] : 'test',
            isset($options['debug']) ? $options['debug'] : true
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootKernel();
    }

}
