<?php

namespace RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use RS\DiExtraBundle\Annotation\DoctrineRepository;
use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Entity\Product;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @DoctrineRepository()
 */
class ProductRepository extends ServiceEntityRepository implements ContainerAwareInterface
{
    public $injectParams;

    public $container;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @InjectParams()
     */
    public function injectParams($fooStaticFactory, $fooStaticFactory2, $fooStaticFactory3)
    {
        $this->injectParams = array(
            'fooStaticFactory' => $fooStaticFactory,
            'fooStaticFactory2' => $fooStaticFactory2,
            'fooStaticFactory3' => $fooStaticFactory3,
        );
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
