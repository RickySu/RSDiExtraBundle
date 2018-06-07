<?php
namespace RS\DiExtraBundle\Finder;

use Symfony\Component\Finder\Finder;

class ClassFinder
{
    protected $pattern;
    protected $pathnamePattern;

    public function __construct($dir, $pattern = null, $pathnamePattern = '*.php')
    {
        $this->dir = $dir;
        $this->pattern = $pattern;
        $this->pathnamePattern = $pathnamePattern;
    }

    public function find()
    {
        $finder = new Finder();
        $finder
            ->files()
            ->in($this->dir)
            ->name($this->pathnamePattern)
            ->ignoreVCS(true)
            ;

        if($this->pattern !== null){
            $finder
                ->filter(function(\SplFileInfo $file){
                    return 0 < preg_match($this->pattern, file_get_contents($file->getPathname()));
                });
        }

        foreach($finder as $file){
            yield realpath($file->getPathname());
        }
    }
}
