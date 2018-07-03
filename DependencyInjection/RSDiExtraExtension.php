<?php
namespace RS\DiExtraBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class RSDiExtraExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $container->setParameter('rs_di_extra.all_bundles', $config['locations']['all_bundles']);
        $container->setParameter('rs_di_extra.bundles', $config['locations']['bundles']);
        $container->setParameter('rs_di_extra.disallow_bundles', array_merge(array('RSDiExtraBundle'), $config['locations']['disallow_bundles']));
        $container->setParameter('rs_di_extra.directories', $config['locations']['directories']);
        $container->setParameter('rs_di_extra.doctrine_integration', $config['doctrine_integration']);
    }
}
