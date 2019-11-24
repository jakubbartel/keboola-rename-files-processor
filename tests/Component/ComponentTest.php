<?php

namespace Keboola\RenameFilesProcessor\Tests\Component;

use Keboola\RenameFilesProcessor\Component;
use Keboola\RenameFilesProcessor\Exception\UserException;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class ComponentTest extends TestCase
{

    public function testInvalidConfigRenamePattern() : void
    {
        $fileSystem = vfsStream::setup('root');
        $dir = vfsStream::newDirectory('data');
        $fileSystem->addChild($dir);
        vfsStream::copyFromFileSystem(__DIR__ . '/test_run_1/data', $dir);

        putenv('KBC_DATADIR=' . $fileSystem->url() . '/data');

        $this->expectException(UserException::class);

        $component = new Component();
        $component->run();
    }

}
