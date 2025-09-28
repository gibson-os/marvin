<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Command\Model;

use GibsonOS\Core\Attribute\Command\Argument;
use GibsonOS\Core\Command\AbstractCommand;
use GibsonOS\Core\Exception\Lock\LockException;
use GibsonOS\Core\Exception\Lock\UnlockException;
use GibsonOS\Core\Exception\Model\SaveError;
use GibsonOS\Core\Exception\Repository\SelectError;
use GibsonOS\Core\Exception\ViolationException;
use GibsonOS\Core\Exception\WebException;
use GibsonOS\Core\Service\DateTimeService;
use GibsonOS\Core\Service\LockService;
use GibsonOS\Core\Wrapper\ModelWrapper;
use GibsonOS\Module\Marvin\Client\ChatClient;
use GibsonOS\Module\Marvin\Repository\Chat\Prompt\ResponseRepository;
use GibsonOS\Module\Marvin\Repository\ModelRepository;
use JsonException;
use MDO\Exception\ClientException;
use MDO\Exception\RecordException;
use Psr\Log\LoggerInterface;
use ReflectionException;

class ProcessCommand extends AbstractCommand
{
    private const LOCK_PREFIX = 'marvinProcessModel';

    #[Argument('Run process for AI model')]
    private int $modelId;

    public function __construct(
        private readonly ModelWrapper $modelWrapper,
        private readonly ResponseRepository $responseRepository,
        private readonly ModelRepository $modelRepository,
        private readonly LockService $lockService,
        private readonly ChatClient $chatClient,
        private readonly DateTimeService $dateTimeService,
        LoggerInterface $logger,
    ) {
        parent::__construct($logger);
    }

    /**
     * @throws ClientException
     * @throws JsonException
     * @throws LockException
     * @throws RecordException
     * @throws ReflectionException
     * @throws SelectError
     * @throws UnlockException
     * @throws WebException
     * @throws SaveError
     * @throws ViolationException
     */
    protected function run(): int
    {
        $lockName = self::LOCK_PREFIX . $this->modelId;

        if ($this->lockService->isLocked($lockName)) {
            return self::ERROR;
        }

        $this->lockService->lock($lockName);
        $model = $this->modelRepository->getById($this->modelId);

        foreach ($this->responseRepository->getWithoutResponseForModel($model) as $response) {
            $response
                ->setStartedAt($this->dateTimeService->get())
            ;
            $this->modelWrapper->getModelManager()->saveWithoutChildren($response);
            $apiResponse = $this->chatClient->postChat($model, $response->getPrompt());
            $response
                ->setMessage($apiResponse['message']['content'])
                ->setDoneAt($this->dateTimeService->get())
            ;
            $this->modelWrapper->getModelManager()->saveWithoutChildren($response);
        }

        $this->lockService->unlock($lockName);

        return self::SUCCESS;
    }

    public function setModelId(int $modelId): ProcessCommand
    {
        $this->modelId = $modelId;

        return $this;
    }
}
