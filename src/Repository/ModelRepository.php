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
}
