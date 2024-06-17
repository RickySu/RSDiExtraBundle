<?php
namespace RS\DiExtraBundle\Tests\Functional;

use Doctrine\ORM\EntityManagerInterface;
use RS\DiExtraBundle\Tests\BaseKernelTestCase;
use RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Entity\Product;

class DoctrineTest extends BaseKernelTestCase
{
    public function test_RepositoryInject()
    {
        //arrange
        $doctrine = $this->container->get(EntityManagerInterface::class);

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
}
