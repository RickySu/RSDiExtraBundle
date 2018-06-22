<?php
namespace RS\DiExtraBundle\Tests;

use RS\DiExtraBundle\Tests\Funtional\AppKernel;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\WebTestCase;

abstract class BaseWebTestCase extends WebTestCase
{
    use BaseTestTrait;

    protected static function createKernel(array $options = array())
    {
        return new AppKernel(
            isset($options['config']) ? $options['config'] : 'default.yml',
            isset($options['debug']) ? (bool) $options['debug'] : true
        );
    }
}
