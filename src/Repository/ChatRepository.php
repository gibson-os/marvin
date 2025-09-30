<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Repository;

use GibsonOS\Core\Dto\Model\ChildrenMapping;
use GibsonOS\Core\Repository\AbstractRepository;
use GibsonOS\Module\Marvin\Model\Chat;
use JsonException;
use MDO\Enum\OrderDirection;
use MDO\Exception\ClientException;
use MDO\Exception\RecordException;
use ReflectionException;

class ChatRepository extends AbstractRepository
{
    /**
     * @throws JsonException
     * @throws ClientException
     * @throws RecordException
     * @throws ReflectionException
     *
     * @return Chat[]
     */
    public function getAllWithTemporaryName(): array
    {
        return $this->fetchAll(
            '`temporary_name`=?',
            [1],
            Chat::class,
            orderBy: ['`p`.`created_at`' => OrderDirection::ASC],
            children: [
                new ChildrenMapping('prompts', 'p_', 'p'),
            ],
        );
    }
}
