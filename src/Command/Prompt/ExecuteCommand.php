<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Command\Prompt;

use GibsonOS\Core\Attribute\Command\Lock;
use GibsonOS\Core\Attribute\Install\Cronjob;
use GibsonOS\Core\Command\AbstractCommand;
use GibsonOS\Core\Exception\Model\SaveError;
use GibsonOS\Core\Exception\ViolationException;
use GibsonOS\Core\Exception\WebException;
use GibsonOS\Core\Service\DateTimeService;
use GibsonOS\Core\Wrapper\ModelWrapper;
use GibsonOS\Module\Marvin\Client\ChatClient;
use GibsonOS\Module\Marvin\Repository\Chat\PromptRepository;
use JsonException;
use MDO\Exception\ClientException;
use MDO\Exception\RecordException;
use Psr\Log\LoggerInterface;
use ReflectionException;

/**
 * @description Start all model processors
 */
#[Cronjob]
#[Lock('marvinExecutePrompt')]
class ExecuteCommand extends AbstractCommand
{
    public function __construct(
        private readonly ModelWrapper $modelWrapper,
        private readonly PromptRepository $promptRepository,
        private readonly ChatClient $chatClient,
        private readonly DateTimeService $dateTimeService,
        LoggerInterface $logger,
    ) {
        parent::__construct($logger);
    }

    /**
     * @throws SaveError
     * @throws ViolationException
     * @throws WebException
     * @throws JsonException
     * @throws ClientException
     * @throws RecordException
     * @throws ReflectionException
     */
    protected function run(): int
    {
        foreach ($this->promptRepository->getWithoutResponseForModel() as $prompt) {
            foreach ($prompt->getResponses() as $response) {
                $response->setStartedAt($this->dateTimeService->get());
                $this->modelWrapper->getModelManager()->saveWithoutChildren($response);
                $apiResponse = $this->chatClient->postChat($response->getModel(), $prompt);
                $response
                    ->setMessage($apiResponse['message']['content'])
                    ->setDoneAt($this->dateTimeService->get())
                ;
                $this->modelWrapper->getModelManager()->saveWithoutChildren($response);
            }
        }

        return self::SUCCESS;
    }
}
