<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Repository;

use GibsonOS\Core\Exception\Repository\SelectError;
use GibsonOS\Core\Repository\AbstractRepository;
use GibsonOS\Module\Marvin\Model\Model;
use JsonException;
use MDO\Exception\ClientException;
use MDO\Exception\RecordException;
use ReflectionException;

class ModelRepository extends AbstractRepository
{
    /**
     * @throws SelectError
     * @throws JsonException
     * @throws ClientException
     * @throws RecordException
     * @throws ReflectionException
     */
    public function getById(int $id): Model
    {
        return $this->fetchOne('`id`=:id', ['id' => $id], Model::class);
    }

    /**
     * @throws ClientException
     * @throws JsonException
     * @throws RecordException
     * @throws ReflectionException
     *
     * @return Model[]
     */
    public function findByName(string $name): array
    {
        return $this->fetchAll(
            '`active`=:active AND `name` LIKE :name',
            [
                'active' => 1,
                'name' => $name . '%',
            ],
            Model::class,
        );
    }

    public function getAll(): array
    {
        return $this->fetchAll('', [], Model::class);
    }
}
