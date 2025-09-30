<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Service;

use GibsonOS\Core\Attribute\GetSetting;
use GibsonOS\Core\Model\Setting;
use GibsonOS\Module\Marvin\Model\Model;
use GibsonOS\Module\Marvin\Repository\ModelRepository;

class ModelService
{
    public function __construct(
        private readonly ModelRepository $modelRepository,
        #[GetSetting('systemModel', 'marvin')]
        private readonly ?Setting $defaultModelSetting,
    ) {
    }

    public function getSystemModel(): ?Model
    {
        if ($this->defaultModelSetting === null) {
            return null;
        }

        return $this->modelRepository->getById((int) $this->defaultModelSetting->getValue());
    }
}
