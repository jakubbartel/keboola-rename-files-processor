<?php declare(strict_types = 1);

namespace Keboola\RenameFilesProcessor;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

class Processor
{

    /**
     * @var string
     */
    private $pattern;

    /**
     * @var string
     */
    private $replacement;

    /**
     * Extractor constructor.
     *
     * @param string $pattern
     * @param string $replacement
     */
    public function __construct(string $pattern, string $replacement)
    {
        $this->pattern = $pattern;
        $this->replacement = $replacement;
    }

    /**
     * @param string $filePath
     * @return string
     */
    private function rename(string $filePath): string
    {
        return preg_replace(sprintf('/%s/', $this->pattern), $this->replacement, $filePath);
    }

    /**
     * @param string $inDirPath
     * @param string $outDirPath
     * @return $this
     */
    private function processFiles(string $inDirPath, string $outDirPath): self
    {
        $finder = new Finder();
        $finder->files()->in($inDirPath);

        $fs = new FileSystem();

        foreach($finder as $file) {
            $renamedFilename = $this->rename($file->getRelativePathname());

            if($this->ensureDir($outDirPath, $renamedFilename)) {
                // $fs->rename($file->getPathname(), sprintf('%s/%s', $outDirPath, $renamedFilename));
                // use copy to pass tests: rename() cannot rename files to vfs://
                $fs->copy($file->getPathname(), sprintf('%s/%s', $outDirPath, $renamedFilename));
            }
        }

        return $this;
    }

    /**
     * @param string $dirPath
     * @param string $filePath
     * @return bool ensure succeeded, directory is ready
     */
    private function ensureDir(string $dirPath, string $filePath): bool
    {
        $matches = [];

        // parse directory from file path
        preg_match('/^(.*\/)?[^\/]*$/', $filePath, $matches);

        if(isset($matches[1]) && $matches[1] !== '') {
            $path = sprintf('%s/%s', $dirPath, $matches[1]);

            if(!file_exists($path)) {
                return mkdir($path, 0777, true);
            } else if(!is_dir($path)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $inDirPath
     * @param string $outDirPath
     * @return Processor
     */
    public function processDir(string $inDirPath, string $outDirPath): self
    {
        return $this->processFiles($inDirPath, $outDirPath);
    }

}
