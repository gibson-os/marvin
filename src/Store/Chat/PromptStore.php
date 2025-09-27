<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Store\Chat;

use GibsonOS\Core\Model\User;
use GibsonOS\Core\Store\AbstractDatabaseStore;
use GibsonOS\Module\Marvin\Model\Chat;
use GibsonOS\Module\Marvin\Model\Chat\Prompt;

/**
 * @extends AbstractDatabaseStore<Prompt>
 */
class PromptStore extends AbstractDatabaseStore
{
    private Chat $chat;

    protected function getModelClassName(): string
    {
        return User::class;
    }

    public function setChat(Chat $chat): PromptStore
    {
        $this->chat = $chat;

        return $this;
    }

    protected function setWheres(): void
    {
        $this->addWhere('`chat_id`=:chatId', ['chatId' => $this->chat->getId()]);
    }
}
