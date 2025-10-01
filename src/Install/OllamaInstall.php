<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Install;

use Generator;
use GibsonOS\Core\Dto\Install\Configuration;
use GibsonOS\Core\Install\AbstractInstall;
use GibsonOS\Core\Install\SingleInstallInterface;
use GibsonOS\Core\Service\InstallService;
use GibsonOS\Core\Service\PriorityInterface;

class OllamaInstall extends AbstractInstall implements PriorityInterface, SingleInstallInterface
{
    public function install(string $module): Generator
    {
        yield $ollamaUrlInput = $this->getEnvInput('OLLAMA_API_URL', 'What is the Ollama API URL?');

        yield (new Configuration('Ollama configuration generated!'))
            ->setValue('OLLAMA_API_URL', $ollamaUrlInput->getValue() ?? '')
        ;
    }

    public function getPart(): string
    {
        return InstallService::PART_CONFIG;
    }

    public function getPriority(): int
    {
        return 800;
    }
}
