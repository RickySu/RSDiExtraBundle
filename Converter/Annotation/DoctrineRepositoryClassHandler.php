<?php
namespace RS\DiExtraBundle\Converter\Annotation;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use RS\DiExtraBundle\Annotation\DoctrineListener;
use RS\DiExtraBundle\Annotation\DoctrineRepository;
use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Annotation\Tag;
use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Exception\InvalidAnnotationException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

class DoctrineRepositoryClassHandler
{
    const DOCTRINE_REPOSITORY_TAG = 'doctrine.repository_service';

    public function handle(ClassMeta $classMeta, \ReflectionClass $reflectionClass, DoctrineRepository $doctrineRepository)
    {
        if(!$reflectionClass->isSubclassOf(ServiceEntityRepository::class)){
            throw new InvalidAnnotationException(sprintf("The \"%s\" entity repository must implements \"%s\"",$reflectionClass->getName(), ServiceEntityRepository::class));
        }

        if($classMeta->id === null) {
            $serviceAnnotation = new Service();
            $serviceAnnotation->private = true;
            $serviceAnnotation->autowire = true;
            (new ServiceClassHandler())->handle($classMeta, $reflectionClass, $serviceAnnotation);
        }

        if($reflectionClass->isSubclassOf(ContainerAwareInterface::class)){
            $classMeta->methodCalls[] = array('setContainer', array(new Reference('service_container', ContainerInterface::NULL_ON_INVALID_REFERENCE)));
        }

        $tagAnnotation = new Tag();
        $tagAnnotation->name = self::DOCTRINE_REPOSITORY_TAG;
        (new TagClassHandler())->handle($classMeta, $reflectionClass, $tagAnnotation);

    }
}
