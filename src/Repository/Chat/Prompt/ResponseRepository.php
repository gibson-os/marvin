<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Repository\Chat\Prompt;

use GibsonOS\Core\Dto\Model\ChildrenMapping;
use GibsonOS\Core\Repository\AbstractRepository;
use GibsonOS\Module\Marvin\Model\Chat\Prompt;
use GibsonOS\Module\Marvin\Model\Chat\Prompt\Response;
use GibsonOS\Module\Marvin\Model\Model;
use JsonException;
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
    public function getAfter(Prompt $prompt, Model $model): array
    {
        return $this->fetchAll(
            '`p`.`chat_id`=:chatId AND `p`.`created_at`>:createdAt AND `t`.`model_id`=:modelId',
            [
                'modelId' => $model->getId(),
                'chatId' => $prompt->getChatId(),
                'createdAt' => $prompt->getCreatedAt()->format('Y-m-d H:i:s'),
            ],
            Response::class,
            children: [
                new ChildrenMapping('prompt', 'p_', 'p'),
            ],
        );
    }
}
