<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Store;

use GibsonOS\Core\Model\User;
use GibsonOS\Core\Store\AbstractDatabaseStore;
use GibsonOS\Module\Marvin\Model\Chat;
use MDO\Enum\OrderDirection;

/**
 * @extends AbstractDatabaseStore<Chat>
 */
class ChatStore extends AbstractDatabaseStore
{
    private User $user;

    protected function getModelClassName(): string
    {
        return Chat::class;
    }

    public function setUser(User $user): ChatStore
    {
        $this->user = $user;

        return $this;
    }

    protected function setWheres(): void
    {
        $this->addWhere('`user_id`=:userId', ['userId' => $this->user->getId()]);
    }

    protected function getDefaultOrder(): array
    {
        return ['`created_at`' => OrderDirection::DESC];
    }
}
