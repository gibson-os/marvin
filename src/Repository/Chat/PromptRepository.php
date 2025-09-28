<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Repository\Chat;

use GibsonOS\Core\Attribute\GetTableName;
use GibsonOS\Core\Dto\Model\ChildrenMapping;
use GibsonOS\Core\Repository\AbstractRepository;
use GibsonOS\Core\Wrapper\RepositoryWrapper;
use GibsonOS\Module\Marvin\Model\Chat\Prompt;
use GibsonOS\Module\Marvin\Model\Chat\Prompt\Response;
use GibsonOS\Module\Marvin\Model\Model;
use JsonException;
use MDO\Dto\Query\Where;
use MDO\Enum\OrderDirection;
use MDO\Exception\ClientException;
use MDO\Exception\RecordException;
use ReflectionException;

class PromptRepository extends AbstractRepository
{
    public function __construct(
        RepositoryWrapper $repositoryWrapper,
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
     *
     * @return Prompt[]
     */
    public function getWithoutModel(Model $model): array
    {
        $responseSelect = $this->getSelectQuery($this->responseTableName, 'r')
            ->setSelects(['count' => 'COUNT(`r`.`prompt_id`)'])
            ->addWhere(new Where('`r`.`prompt_id`=`t`.`id`', []))
            ->addWhere(new Where('`r`.`model_id`=`cm`.`model_id`', []))
        ;

        return $this->fetchAll(
            sprintf('`cm`.`model_id`=:modelId AND (%s)=0', $responseSelect->getQuery()),
            ['modelId' => $model->getId()],
            Prompt::class,
            orderBy: ['`t`.`created_at`' => OrderDirection::ASC, '`cp`.`created_at`' => OrderDirection::ASC],
            children: [
                new ChildrenMapping('chat', 'c_', 'c', [
                    new ChildrenMapping('models', 'cm_', 'cm'),
                    new ChildrenMapping('prompts', 'cp_', 'cp', [
                        new ChildrenMapping('responses', 'cmr_', 'cmr'),
                    ]),
                ]),
            ],
        );
    }
}
