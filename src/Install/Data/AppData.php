<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Install\Data;

use Generator;
use GibsonOS\Core\Dto\Install\Success;
use GibsonOS\Core\Exception\Model\SaveError;
use GibsonOS\Core\Exception\Repository\SelectError;
use GibsonOS\Core\Install\AbstractInstall;
use GibsonOS\Core\Service\InstallService;
use GibsonOS\Core\Service\PriorityInterface;
use JsonException;
use MDO\Exception\ClientException;
use MDO\Exception\RecordException;
use ReflectionException;

class AppData extends AbstractInstall implements PriorityInterface
{
    /**
     * @throws JsonException
     * @throws SaveError
     * @throws SelectError
     * @throws ClientException
     * @throws RecordException
     * @throws ReflectionException
     */
    public function install(string $module): Generator
    {
        $this->addApp('Marvin', 'marvin', 'index', 'index', 'icon_exe');

        yield new Success('Marvin apps installed!');
    }

    public function getPart(): string
    {
        return InstallService::PART_DATA;
    }

    public function getModule(): ?string
    {
        return 'marvin';
    }

    public function getPriority(): int
    {
        return 0;
    }
}
