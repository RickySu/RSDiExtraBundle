<?php
namespace RS\DiExtraBundle\DependencyInjection\Compiler;

use RS\DiExtraBundle\Annotation\AutoDiscoverBundleInterface;
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
            if(class_exists($bundleClass)){
                $reflect = new \ReflectionClass($bundleClass);
                if($reflect->isSubclassOf(AutoDiscoverBundleInterface::class)) {
                    $directories[] = $this->findBundleDirectory($bundleClass);
                    continue;
                }
            }

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

    protected function findClassFiles($directories, $excludeDirectories, $excludeFiles)
    {
        $iterator = new \AppendIterator();

        foreach ($directories as $directory){
            $finder = new ClassFileFinder($directory);
            $finder
                ->setExcludeDirPattern($excludeDirectories)
                ->setExcludePathnamePattern($excludeFiles);
            $iterator->append($finder->find());
        }

        return $iterator;
    }

    protected function handleClassFiles(ContainerBuilder $container, $directories)
    {
        $converter = $container->get('rs_di_extra.definition_converter');
        $excludeDirectories = $container->getParameter('rs_di_extra.exclude_directories');
        $excludeFiles = $container->getParameter('rs_di_extra.exclude_files');
        $this->addDirectoriesResources($container, $directories);
        foreach ($this->findClassFiles($directories, $excludeDirectories, $excludeFiles) as $classFile){
            $container->addResource(new FileResource($classFile));
            try {
                foreach ($converter->convert($classFile) as $id => $definition) {
                    $container->setDefinition($id, $definition);
                    if ($id != $definition->getClass()) {
                        $container->setAlias($definition->getClass(), $id);
                    }
                }
            }
            catch (\RuntimeException $e) {
                continue;
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
