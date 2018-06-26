<?php

namespace Keboola\RenameFilesProcessor\Tests\Processor;

use Keboola\RenameFilesProcessor;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class ProcessorTest extends TestCase
{

    public function testProcessFiles() : void
    {
        $pattern = "file";
        $replacement = "renamed";

        $processor = new RenameFilesProcessor\Processor($pattern, $replacement);

        $fileSystem = vfsStream::setup();

        $processor->processDir(
            __DIR__ . '/fixtures/files/in/files',
            $fileSystem->url()
        );

        $this->assertEquals(
            scandir($fileSystem->url()),
            [
                '.',
                '..',
                'renamed.csv',
                'renamed2.csv',
            ]
        );

        $this->assertFileEquals(
            __DIR__ . '/fixtures/files/out/files/renamed.csv',
            $fileSystem->url() . '/renamed.csv'
        );

        $this->assertFileEquals(
            __DIR__ . '/fixtures/files/out/files/renamed2.csv',
            $fileSystem->url() . '/renamed2.csv'
        );
    }

    public function testProcessDir() : void
    {
        $pattern = "mydir(\d+)\/file(\d+)";
        $replacement = "yourdir$1/renamed$2";

        $processor = new RenameFilesProcessor\Processor($pattern, $replacement);

        $fileSystem = vfsStream::setup();

        $processor->processDir(
            __DIR__ . '/fixtures/directory/in/files',
            $fileSystem->url()
        );

        $this->assertEquals(
            scandir($fileSystem->url()),
            [
                '.',
                '..',
                'yourdir1',
                'yourdir2',
            ]
        );

        $this->assertFileEquals(
            __DIR__ . '/fixtures/directory/out/files/yourdir1/renamed1.csv',
            $fileSystem->url() . '/yourdir1/renamed1.csv'
        );

        $this->assertFileEquals(
            __DIR__ . '/fixtures/directory/out/files/yourdir1/renamed2.csv',
            $fileSystem->url() . '/yourdir1/renamed2.csv'
        );

        $this->assertFileEquals(
            __DIR__ . '/fixtures/directory/out/files/yourdir2/renamed1.csv',
            $fileSystem->url() . '/yourdir2/renamed1.csv'
        );

        $this->assertFileEquals(
            __DIR__ . '/fixtures/directory/out/files/yourdir2/renamed2.csv',
            $fileSystem->url() . '/yourdir2/renamed2.csv'
        );
    }

    public function testProcessDirPattern() : void
    {
        $pattern = "(\w+)-(\d+)";
        $replacement = "first/$2/$1";

        $processor = new RenameFilesProcessor\Processor($pattern, $replacement);

        $fileSystem = vfsStream::setup();

        $processor->processDir(
            __DIR__ . '/fixtures/dirpattern/in/files',
            $fileSystem->url()
        );

        $this->assertEquals(
            [
                '.',
                '..',
                'first',
            ],
            scandir($fileSystem->url())
        );

        $this->assertEquals(
            [
                '.',
                '..',
                '1',
                '2',
            ],
            scandir($fileSystem->url() . '/first')
        );

        $this->assertEquals(
            [
                '.',
                '..',
                'a.csv',
                'b.csv',
            ],
            scandir($fileSystem->url() . '/first/1')
        );

        $this->assertEquals(
            [
                '.',
                '..',
                'a.csv',
                'b.csv',
            ],
            scandir($fileSystem->url() . '/first/2')
        );

        $this->assertFileEquals(
            __DIR__ . '/fixtures/dirpattern/out/files/first/1/a.csv',
            $fileSystem->url() . '/first/1/a.csv'
        );

        $this->assertFileEquals(
            __DIR__ . '/fixtures/dirpattern/out/files/first/1/b.csv',
            $fileSystem->url() . '/first/1/b.csv'
        );

        $this->assertFileEquals(
            __DIR__ . '/fixtures/dirpattern/out/files/first/2/a.csv',
            $fileSystem->url() . '/first/2/a.csv'
        );

        $this->assertFileEquals(
            __DIR__ . '/fixtures/dirpattern/out/files/first/2/b.csv',
            $fileSystem->url() . '/first/2/b.csv'
        );
    }

}
