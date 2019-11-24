<?php declare(strict_types = 1);

namespace Keboola\RenameFilesProcessor;

use Keboola\Component\BaseComponent;
use Keboola\RenameFilesProcessor\Exception;

class Component extends BaseComponent
{

    /**
     * @return string
     */
    protected function getConfigDefinitionClass(): string
    {
        return ConfigDefinition::class;
    }

    /**
     * The source of life.
     *
     * @throws Exception\UserException
     */
    public function run() : void
    {
        try {
            $processor = new Processor(
                $this->getConfig()->getValue(['parameters', 'pattern']),
                $this->getConfig()->getValue(['parameters', 'replacement'])
            );
        } catch(Exception\InvalidPatternException $e) {
            throw new Exception\UserException($e->getMessage(), 0, $e);
        }

        $processor->processDir(
            sprintf('%s%s', $this->getDataDir(), '/in/files'),
            sprintf('%s%s', $this->getDataDir(), '/out/files')
        );
    }

}
