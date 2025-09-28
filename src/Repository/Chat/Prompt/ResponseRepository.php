<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Repository\Chat\Prompt;

use GibsonOS\Core\Dto\Model\ChildrenMapping;
use GibsonOS\Core\Repository\AbstractRepository;
use GibsonOS\Module\Marvin\Model\Chat\Prompt\Response;
use GibsonOS\Module\Marvin\Model\Model;
use JsonException;
use MDO\Enum\OrderDirection;
use MDO\Exception\ClientException;
use MDO\Exception\RecordException;
use ReflectionException;

class ResponseRepository extends AbstractRepository
{
    /**
     * @throws JsonException
     * @throws ClientException
     * @throws RecordException
     * @throws ReflectionException
     *
     * @return Response[]
     */
    public function getWithoutResponseForModel(Model $model): array
    {
        return $this->fetchAll(
            '`t`.`model_id`=:modelId AND `t`.`started_at` IS NULL',
            ['modelId' => $model->getId()],
            Response::class,
            orderBy: ['`p`.`created_at`' => OrderDirection::ASC],
            children: [
                new ChildrenMapping('prompt', 'p_', 'p'),
            ],
        );
    }
}
