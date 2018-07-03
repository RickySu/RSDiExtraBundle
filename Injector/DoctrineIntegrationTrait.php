<?php
namespace RS\DiExtraBundle\Injector;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait DoctrineIntegrationTrait
{
    /** @var ContainerInterface */
    protected $container;

    /** @var EntityManagerInterface */
    protected $entityManagerProxy;

    protected $repositoryInjectParameters = array();

    public function __construct()
    {
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManagerProxy = $entityManager;
    }

    public function __call($name, $arguments)
    {
        $methodDefine = array_shift($arguments);

        if(!isset($this->repositoryInjectParameters[$methodDefine[0]])){
            $this->repositoryInjectParameters[$methodDefine[0]] = array();
        }

        $this->repositoryInjectParameters[$methodDefine[0]][$methodDefine[1]] = $arguments;
    }

    /**
     * Gets the repository for an entity class.
     *
     * @param string $entityName The name of the entity.
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository The repository class.
     */
    public function getRepository($entityName)
    {
        $repository = $this->entityManagerProxy->getRepository($entityName);
        $repositoryClass = get_class($repository);

        if(!$repository instanceof EntityRepository){
            return $repository;
        }

        if($repository instanceof ContainerAwareInterface){
            $repository->setContainer($this->container);
            return $repository;
        }

        if(isset($this->repositoryInjectParameters[$repositoryClass])){
            foreach($this->repositoryInjectParameters[$repositoryClass] as $injectMethod => $arguments){
                $repository->$injectMethod(...$arguments);
            }
        }

        return $repository;
    }
}
