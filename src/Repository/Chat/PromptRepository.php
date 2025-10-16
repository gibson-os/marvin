<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Repository\Chat;

use GibsonOS\Core\Attribute\GetTableName;
use GibsonOS\Core\Dto\Model\ChildrenMapping;
use GibsonOS\Core\Exception\Repository\SelectError;
use GibsonOS\Core\Repository\AbstractRepository;
use GibsonOS\Core\Wrapper\RepositoryWrapper;
use GibsonOS\Module\Marvin\Model\Chat;
use GibsonOS\Module\Marvin\Model\Chat\Prompt;
use GibsonOS\Module\Marvin\Model\Chat\Prompt\Response;
use GibsonOS\Module\Marvin\Model\Model;
use JsonException;
use MDO\Dto\Query\Join;
use MDO\Dto\Query\Where;
use MDO\Enum\JoinType;
use MDO\Enum\OrderDirection;
use MDO\Exception\ClientException;
use MDO\Exception\RecordException;
use ReflectionException;

class PromptRepository extends AbstractRepository
{
    public function __construct(
        RepositoryWrapper $repositoryWrapper,
        #[GetTableName(Prompt::class)]
        private readonly string $promptTableName,
        #[GetTableName(Response::class)]
        private readonly string $responseTableName,
    ) {
        parent::__construct($repositoryWrapper);
    }

    /**
     * @throws JsonException
     * @throws ClientException
     * @throws RecordException
     * @throws ReflectionException
     * @throws SelectError
     */
    public function getWithoutResponse(): Prompt
    {
        return $this->fetchOne(
            '`r`.`done_at` IS NULL',
            [],
            Prompt::class,
            ['`t`.`created_at`' => OrderDirection::ASC, '`t`.`id`' => OrderDirection::ASC],
            [
                new ChildrenMapping('responses', 'r_', 'r', [
                    new ChildrenMapping('model', 'm_', 'm'),
                ]),
                new ChildrenMapping('images', 'i_', 'i'),
            ],
        );
    }

    /**
     * @throws ClientException
     * @throws JsonException
     * @throws RecordException
     * @throws ReflectionException
     *
     * @return Prompt[]
     */
    public function getWithMissingResponse(Chat $chat, Model $model): array
    {
        $select = $this->getSelectQuery($this->promptTableName, 'p')
            ->addJoin(new Join(
                $this->getTable($this->responseTableName),
                'r',
                '`r`.`prompt_id` = `p`.`id` AND `r`.`model_id`=:modelId',
                JoinType::LEFT,
            ))
            ->addWhere(new Where('`r`.`id` IS NULL', ['modelId' => $model->getId()]))
            ->addWhere(new Where('`p`.`chat_id`=:chatId', ['chatId' => $chat->getId()]))
        ;

        return $this->getModels($select, Prompt::class, children: [
            new ChildrenMapping('responses', 'pr_', 'pr'),
        ]);
    }
}
