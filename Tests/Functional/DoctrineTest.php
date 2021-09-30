<?php
namespace RS\DiExtraBundle\Tests\Functional;

use Psr\Container\ContainerInterface;
use RS\DiExtraBundle\Tests\BaseKernelTestCase;
use RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Entity\Product;

class DoctrineTest extends BaseKernelTestCase
{
    public function test_RepositoryInject()
    {
        //arrange
        $doctrine = self::getContainer()->get('doctrine');

        //act
        $repository = $doctrine->getRepository(Product::class);
        $ids = array_map(function($service){
            return $service->params['id'];
        }, $repository->injectParams);
        sort($ids);

        //assert
        $this->assertEquals(array(
            'foo_static_factory',
            'foo_static_factory2',
            'foo_static_factory3',
        ), $ids);
    }

    public function test_ContainerAwareInterface()
    {
        //arrange
        $doctrine = self::getContainer()->get('doctrine');

        //act
        $repository = $doctrine->getRepository(Product::class);

        //assert
        $this->assertInstanceOf(ContainerInterface::class, $repository->container);
    }

}
