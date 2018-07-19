<?php

namespace RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use RS\DiExtraBundle\Annotation\DoctrineRepository;
use RS\DiExtraBundle\Annotation\Inject;
use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Annotation\Tag;
use RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Entity\Product;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @DoctrineRepository()
 */
class ProductRepository extends ServiceEntityRepository implements ContainerAwareInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function setContainer(ContainerInterface $container = null)
    {

    }
}
