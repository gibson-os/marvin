<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Store;

use GibsonOS\Core\Store\AbstractDatabaseStore;
use GibsonOS\Module\Marvin\Model\Model;

/**
 * @extends AbstractDatabaseStore<Model>
 */
class ModelStore extends AbstractDatabaseStore
{
    protected function getModelClassName(): string
    {
        return Model::class;
    }

    protected function setWheres(): void
    {
        $this->addWhere('`m`.`active`=:active', ['active' => 1]);
    }
}
