<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\AutoComplete;

use GibsonOS\Core\AutoComplete\AutoCompleteInterface;
use GibsonOS\Module\Marvin\Model\Model;
use GibsonOS\Module\Marvin\Repository\ModelRepository;

class ModelAutoComplete implements AutoCompleteInterface
{
    public function __construct(private readonly ModelRepository $modelRepository)
    {
    }

    public function getByNamePart(string $namePart, array $parameters): array
    {
        return $this->modelRepository->findByName($namePart);
    }

    public function getById(string $id, array $parameters): Model
    {
        return $this->modelRepository->getById((int) $id);
    }

    public function getModel(): string
    {
        return 'GibsonOS.module.marvin.model.Model';
    }

    public function getValueField(): string
    {
        return 'id';
    }

    public function getDisplayField(): string
    {
        return 'name';
    }
}
