<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Command;

use GibsonOS\Core\Attribute\Install\Cronjob;
use GibsonOS\Core\Command\AbstractCommand;
use GibsonOS\Core\Manager\ModelManager;
use GibsonOS\Module\Marvin\Client\ModelClient;
use Psr\Log\LoggerInterface;

/**
 * @description Collect all possible KI models from Ollama
 */
#[Cronjob('12', '42', '0')]
class CollectModelsCommand extends AbstractCommand
{
    public function __construct(
        private readonly ModelClient $client,
        private readonly ModelManager $modelManager,
        LoggerInterface $logger,
    ) {
        parent::__construct($logger);
    }

    protected function run(): int
    {
        foreach ($this->client->getModels() as $model) {
            $this->modelManager->saveWithoutChildren($model);
        }

        return self::SUCCESS;
    }
}
