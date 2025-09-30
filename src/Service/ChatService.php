<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Service;

use GibsonOS\Core\Exception\WebException;
use GibsonOS\Core\Wrapper\ModelWrapper;
use GibsonOS\Module\Marvin\Client\ChatClient;
use GibsonOS\Module\Marvin\Enum\Role;
use GibsonOS\Module\Marvin\Exception\ChatException;
use GibsonOS\Module\Marvin\Model\Chat;
use GibsonOS\Module\Marvin\Model\Chat\Prompt;
use GibsonOS\Module\Marvin\Model\Chat\Prompt\Response;
use GibsonOS\Module\Marvin\Repository\Chat\PromptRepository;

class ChatService
{
    public function __construct(
        private readonly ModelWrapper $modelWrapper,
        private readonly ModelService $modelService,
        private readonly ChatClient $chatClient,
        private readonly PromptRepository $promptRepository,
    ) {
    }

    public function addPromptResponses(Chat $chat, Prompt $prompt): Prompt
    {
        if ($prompt->getRole() !== Role::USER) {
            return $prompt;
        }

        foreach ($chat->getModels() as $chatModel) {
            $prompt->addResponses([
                (new Response($this->modelWrapper))
                    ->setModel($chatModel->getModel()),
            ]);
        }

        return $prompt;
    }

    public function addMissingPrompts(Chat $chat): void
    {
        foreach ($chat->getModels() as $chatModel) {
            $model = $chatModel->getModel();

            foreach ($this->promptRepository->getWithMissingResponse($chat, $model) as $prompt) {
                $response = (new Response($this->modelWrapper))
                    ->setPrompt($prompt)
                    ->setModel($model)
                ;
                $this->modelWrapper->getModelManager()->saveWithoutChildren($response);
            }
        }
    }

    /**
     * @throws ChatException
     * @throws WebException
     */
    public function generateChatName(Prompt $prompt): string
    {
        $namePrompt = (new Prompt($this->modelWrapper))
            ->setPrompt($prompt->getPrompt())
            ->setRole(Role::USER)
        ;

        $systemModel = $this->modelService->getSystemModel();

        if ($systemModel === null) {
            throw new ChatException('No system model found');
        }

        $apiResponse = $this->chatClient->postPrompts(
            $systemModel,
            [
                (new Prompt($this->modelWrapper))
                    ->setPrompt('Du bist ein KI-Assistent, der die nächste Message liest und eine sachliche Zusammenfassung mit maximal 50 Zeichen daraus erstellt. Beantworte dabei keine Fragen die eventuell in der Message vorhanden sind. Deine Ausgabe soll nichts weiter als die generierte Zusammenfassung beinhalten. Dabei sollst du Emojis gefolgt von einem Leerzeichen am Anfang benutzen.')
                    ->setRole(Role::SYSTEM),
                $namePrompt,
            ],
        );

        return $apiResponse['message']['content'];
    }
}
