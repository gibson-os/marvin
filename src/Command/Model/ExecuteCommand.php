<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Command\Model;

use GibsonOS\Core\Attribute\Install\Cronjob;
use GibsonOS\Core\Command\AbstractCommand;
use GibsonOS\Core\Service\CommandService;
use GibsonOS\Module\Marvin\Store\ModelStore;
use Psr\Log\LoggerInterface;

/**
 * @description Start all model processors
 */
#[Cronjob]
class ExecuteCommand extends AbstractCommand
{
    public function __construct(
        private readonly CommandService $commandService,
        private readonly ModelStore $modelStore,
        LoggerInterface $logger,
    ) {
        parent::__construct($logger);
    }

    protected function run(): int
    {
        foreach ($this->modelStore->getList() as $model) {
            $this->commandService->execute(
                ProcessCommand::class,
                [
                    'modelId' => (string) $model->getId(),
                ],
            );
        }

        return self::SUCCESS;
    }
}
