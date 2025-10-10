<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Store\Chat;

use GibsonOS\Core\Dto\Model\ChildrenMapping;
use GibsonOS\Core\Store\AbstractDatabaseStore;
use GibsonOS\Module\Marvin\Model\Chat;
use GibsonOS\Module\Marvin\Model\Chat\Prompt;
use MDO\Enum\OrderDirection;

/**
 * @extends AbstractDatabaseStore<Prompt>
 */
class PromptStore extends AbstractDatabaseStore
{
    private Chat $chat;

    protected function getModelClassName(): string
    {
        return Prompt::class;
    }

    public function setChat(Chat $chat): PromptStore
    {
        $this->chat = $chat;

        return $this;
    }

    protected function setWheres(): void
    {
        $this->addWhere('`p`.`chat_id`=:chatId', ['chatId' => $this->chat->getId()]);
    }

    protected function getAlias(): ?string
    {
        return 'p';
    }

    protected function getDefaultOrder(): array
    {
        return [
            '`p`.`created_at`' => OrderDirection::ASC,
            '`p`.`id`' => OrderDirection::ASC,
            '`m`.`name`' => OrderDirection::ASC,
        ];
    }

    protected function getExtends(): array
    {
        return [
            new ChildrenMapping('responses', 'r_', 'r', [
                new ChildrenMapping('model', 'm_', 'm'),
            ]),
        ];
    }
}
