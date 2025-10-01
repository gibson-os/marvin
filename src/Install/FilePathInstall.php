<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Install;

use Generator;
use GibsonOS\Core\Dto\Install\Success;
use GibsonOS\Core\Exception\CreateError;
use GibsonOS\Core\Exception\Model\SaveError;
use GibsonOS\Core\Exception\Repository\SelectError;
use GibsonOS\Core\Install\AbstractInstall;
use GibsonOS\Core\Service\InstallService;
use GibsonOS\Core\Service\PriorityInterface;
use JsonException;
use MDO\Exception\ClientException;
use MDO\Exception\RecordException;
use ReflectionException;

class FilePathInstall extends AbstractInstall implements PriorityInterface
{
    /**
     * @throws CreateError
     * @throws SaveError
     * @throws SelectError
     * @throws JsonException
     * @throws ClientException
     * @throws RecordException
     * @throws ReflectionException
     */
    public function install(string $module): Generator
    {
        yield $filePathInput = $this->getSettingInput(
            'marvin',
            'filePath',
            'What is the file directory for Marvin?',
        );
        $filePath = $this->dirService->addEndSlash($filePathInput->getValue() ?? '');

        if (!file_exists($filePath)) {
            $this->dirService->create($filePath);
        }

        $this->setSetting('marvin', 'filePath', $filePath);

        yield new Success('Marvin file directory set!');
    }

    public function getPart(): string
    {
        return InstallService::PART_CONFIG;
    }

    public function getModule(): string
    {
        return 'marvin';
    }

    public function getPriority(): int
    {
        return 500;
    }
}
