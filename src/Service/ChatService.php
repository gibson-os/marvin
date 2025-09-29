<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Service;

use GibsonOS\Core\Wrapper\ModelWrapper;
use GibsonOS\Module\Marvin\Enum\Role;
use GibsonOS\Module\Marvin\Model\Chat;
use GibsonOS\Module\Marvin\Model\Chat\Prompt;
use GibsonOS\Module\Marvin\Model\Chat\Prompt\Response;

class ChatService
{
    public function __construct(private readonly ModelWrapper $modelWrapper)
    {
    }

    public function addPrompt(Chat $chat, Prompt $prompt): Chat
    {
        return $chat->addPrompts([$this->addPromptResponses($chat, $prompt)]);
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
}
