<?php
namespace RS\DiExtraBundle\Injector;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait DoctrineIntegrationTrait
{
    /** @var ContainerInterface */
    protected $container;

    /** @var EntityManagerInterface */
    protected $entityManagerProxy;

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManagerProxy = $entityManager;
    }
}