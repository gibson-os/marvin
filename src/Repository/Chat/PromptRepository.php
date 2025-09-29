<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Repository\Chat;

use GibsonOS\Core\Dto\Model\ChildrenMapping;
use GibsonOS\Core\Repository\AbstractRepository;
use GibsonOS\Module\Marvin\Model\Chat\Prompt;
use JsonException;
use MDO\Enum\OrderDirection;
use MDO\Exception\ClientException;
use MDO\Exception\RecordException;
use ReflectionException;

class PromptRepository extends AbstractRepository
{
    /**
     * @throws JsonException
     * @throws ClientException
     * @throws RecordException
     * @throws ReflectionException
     *
     * @return Prompt[]
     */
    public function getWithoutResponseForModel(): array
    {
        return $this->fetchAll(
            '`r`.`started_at` IS NULL',
            [],
            Prompt::class,
            orderBy: ['`t`.`created_at`' => OrderDirection::ASC],
            children: [
                new ChildrenMapping('responses', 'r_', 'r', [
                    new ChildrenMapping('model', 'm_', 'm'),
                ]),
            ],
        );
    }
}
