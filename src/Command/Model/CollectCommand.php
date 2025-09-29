<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Command\Model;

use GibsonOS\Core\Attribute\Install\Cronjob;
use GibsonOS\Core\Command\AbstractCommand;
use GibsonOS\Core\Exception\Model\SaveError;
use GibsonOS\Core\Exception\ViolationException;
use GibsonOS\Core\Manager\ModelManager;
use GibsonOS\Module\Marvin\Client\ModelClient;
use GibsonOS\Module\Marvin\Repository\ModelRepository;
use JsonException;
use MDO\Exception\RecordException;
use Psr\Log\LoggerInterface;
use ReflectionException;

/**
 * @description Collect all possible KI models from Ollama
 */
#[Cronjob('12', '42', '0')]
class CollectCommand extends AbstractCommand
{
    public function __construct(
        private readonly ModelClient $client,
        private readonly ModelManager $modelManager,
        private readonly ModelRepository $modelRepository,
        LoggerInterface $logger,
    ) {
        parent::__construct($logger);
    }

    /**
     * @throws SaveError
     * @throws ViolationException
     * @throws JsonException
     * @throws RecordException
     * @throws ReflectionException
     */
    protected function run(): int
    {
        foreach ($this->modelRepository->getAll() as $model) {
            $this->modelManager->saveWithoutChildren($model->setActive(false));
        }

        foreach ($this->client->getModels() as $model) {
            $this->modelManager->saveWithoutChildren($model->setActive(true));
        }

        return self::SUCCESS;
    }
}
