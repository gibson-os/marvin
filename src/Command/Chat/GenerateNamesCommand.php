<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Command\Chat;

use GibsonOS\Core\Attribute\Command\Lock;
use GibsonOS\Core\Attribute\Install\Cronjob;
use GibsonOS\Core\Command\AbstractCommand;
use GibsonOS\Core\Exception\Model\SaveError;
use GibsonOS\Core\Exception\ViolationException;
use GibsonOS\Core\Exception\WebException;
use GibsonOS\Core\Manager\ModelManager;
use GibsonOS\Module\Marvin\Exception\ChatException;
use GibsonOS\Module\Marvin\Repository\ChatRepository;
use GibsonOS\Module\Marvin\Service\ChatService;
use JsonException;
use MDO\Exception\ClientException;
use MDO\Exception\RecordException;
use Psr\Log\LoggerInterface;
use ReflectionException;

/**
 * @description Generate a name for all chats with a temporary name
 */
#[Cronjob]
#[Lock('marvinGenerateCatsNames')]
class GenerateNamesCommand extends AbstractCommand
{
    public function __construct(
        private readonly ChatService $chatService,
        private readonly ChatRepository $chatRepository,
        private readonly ModelManager $modelManager,
        LoggerInterface $logger,
    ) {
        parent::__construct($logger);
    }

    /**
     * @throws SaveError
     * @throws ViolationException
     * @throws WebException
     * @throws ChatException
     * @throws JsonException
     * @throws ClientException
     * @throws RecordException
     * @throws ReflectionException
     */
    protected function run(): int
    {
        foreach ($this->chatRepository->getAllWithTemporaryName() as $chat) {
            $chat
                ->setName($this->chatService->generateChatName($chat->getPrompts()[0]))
                ->setTemporaryName(false)
            ;
            $this->modelManager->saveWithoutChildren($chat);
        }

        return self::SUCCESS;
    }
}
