<?php
namespace RS\DiExtraBundle\Finder;

use Symfony\Component\Finder\Finder;

class ClassFileFinder
{
    protected $pattern = null;
    protected $pathnamePattern = '*.php';
    protected $excludePathnamePattern = null;
    protected $excludeDirPattern = null;
    protected $dir;

    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    public function find()
    {
        $finder = new Finder();
        $finder->files();
        $finder->in($this->dir);
        $this->applyExcludeDirPattern($finder);
        $finder->name($this->pathnamePattern);
        $this->applyExcludePathnamePattern($finder);
        $finder->ignoreVCS(true);

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

    protected function applyExcludePathnamePattern(Finder $finder)
    {
        if($this->excludePathnamePattern === null || $this->excludePathnamePattern === []){
            return;
        }
        $finder->notName($this->excludePathnamePattern);
    }

    public function setPattern($pattern): ClassFileFinder
    {
        $this->pattern = $pattern;
        return $this;
    }

    public function setPathnamePattern(string $pathnamePattern): ClassFileFinder
    {
        $this->pathnamePattern = $pathnamePattern;
        return $this;
    }

    public function setExcludePathnamePattern($excludePathnamePattern): ClassFileFinder
    {
        $this->excludePathnamePattern = $excludePathnamePattern;
        return $this;
    }

    public function setExcludeDirPattern($excludeDirPattern): ClassFileFinder
    {
        $this->excludeDirPattern = $excludeDirPattern;
        return $this;
    }

    protected function applyExcludeDirPattern(Finder $finder)
    {
        if($this->excludeDirPattern === null || $this->excludeDirPattern === []){
            return;
        }
        $finder->notPath($this->excludeDirPattern);
    }
}
