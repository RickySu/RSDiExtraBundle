<?php
namespace RS\DiExtraBundle\ServiceLocator;

use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ControllerServiceLocatorFactory implements ServiceSubscriberInterface
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public static function getSubscribedServices(): array
    {
        return AbstractController::getSubscribedServices();
    }
}
