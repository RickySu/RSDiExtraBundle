<?php
namespace RS\DiExtraBundle\DependencyInjection\Compiler;

use RS\DiExtraBundle\Finder\ClassFileFinder;
use Symfony\Component\Config\Resource\DirectoryResource;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AnnotationCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $directories = $this->getSearchDirectories($container);
        $this->handleClassFiles($container, $directories);
    }

    protected function getSearchDirectories(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        $allBundles = $container->getParameter('rs_di_extra.all_bundles');
        $allowBundles = $container->getParameter('rs_di_extra.bundles');
        $disallowBundles = $container->getParameter('rs_di_extra.disallow_bundles');
        $directories = $container->getParameter('rs_di_extra.directories');

        foreach ($bundles as $bundleName => $bundleClass){

            if(in_array($bundleName, $disallowBundles)){
                continue;
            }

            if($allBundles){
                $directories[] = $this->findBundleDirectory($bundleClass);
                continue;
            }

            if(!in_array($bundleName, $allowBundles)){
                continue;
            }

            $directories[] = $this->findBundleDirectory($bundleClass);
        }

        return $directories;
    }

    protected function findBundleDirectory($bundleClass)
    {
        $reflected = new \ReflectionClass($bundleClass);
        return dirname($reflected->getFileName());
    }

    protected function findClassFiles($directories)
    {
        $iterator = new \AppendIterator();

        foreach ($directories as $directory){
            $finder = new ClassFileFinder($directory);
            $iterator->append($finder->find());
        }

        return $iterator;
    }

    protected function handleClassFiles(ContainerBuilder $container, $directories)
    {
        $converter = $container->get('rs_di_extra.definition_converter');
        $this->addDirectoriesResources($container, $directories);
        foreach ($this->findClassFiles($directories) as $classFile){
            $container->addResource(new FileResource($classFile));
            foreach ($converter->convert($classFile) as $id => $definition){
                $container->setDefinition($id, $definition);
            }
        }
    }

    protected function addDirectoriesResources(ContainerBuilder $container, $directories)
    {
        foreach ($directories as $directory){
            $container->addResource(new DirectoryResource($directory));
        }
    }

}
