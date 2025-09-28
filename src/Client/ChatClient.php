<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Client;

use GibsonOS\Core\Attribute\GetEnv;
use GibsonOS\Core\Dto\Web\Body;
use GibsonOS\Core\Dto\Web\Request;
use GibsonOS\Core\Enum\HttpMethod;
use GibsonOS\Core\Exception\WebException;
use GibsonOS\Core\Service\WebService;
use GibsonOS\Core\Utility\JsonUtility;
use GibsonOS\Module\Marvin\Enum\Role;
use GibsonOS\Module\Marvin\Model\Chat\Prompt;
use GibsonOS\Module\Marvin\Model\Chat\Prompt\Response;
use GibsonOS\Module\Marvin\Model\Model;

class ChatClient
{
    public function __construct(
        private readonly WebService $webService,
        #[GetEnv('OLLAMA_API_URL')]
        private readonly string $apiUrl,
        #[GetEnv('OLLAMA_API_PORT')]
        private readonly int $apiPort,
    ) {
    }

    /**
     * @throws WebException
     */
    public function postChat(Model $model, Prompt $prompt): array
    {
        $messages = [];

        foreach ($prompt->getChat()->getPrompts() as $oldPrompt) {
            $messages[] = [
                'role' => $oldPrompt->getRole()->value,
                'content' => $oldPrompt->getPrompt(),
            ];

            if ($oldPrompt->getId() === $prompt->getId()) {
                break;
            }

            $modelResponse = array_find(
                $oldPrompt->getResponses(),
                fn (Response $response): bool => $response->getModelId() === $model->getId(),
            );

            if ($modelResponse === null) {
                break;
            }

            $messages[] = [
                'role' => Role::ASSISTANT,
                'content' => $modelResponse->getContent(),
            ];
        }

        $body = JsonUtility::encode([
            'model' => $model->getName(),
            'messages' => $messages,
            'stream' => false,
        ]);
        $response = $this->webService->request(
            (new Request(sprintf('%s%s', $this->apiUrl, 'api/chat')))
                ->setPort($this->apiPort)
                ->setBody((new Body())->setContent($body, strlen($body)))
                ->setMethod(HttpMethod::POST),
        );

        return JsonUtility::decode($response->getBody()->getContent());
    }
}
