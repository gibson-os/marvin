<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Form;

use GibsonOS\Core\Dto\Form;
use GibsonOS\Core\Dto\Form\Button;
use GibsonOS\Core\Dto\Parameter\AbstractParameter;
use GibsonOS\Core\Dto\Parameter\AutoCompleteParameter;
use GibsonOS\Core\Model\Setting;
use GibsonOS\Module\Marvin\AutoComplete\ModelAutoComplete;

class SettingsForm
{
    public function __construct(private readonly ModelAutoComplete $modelAutoComplete)
    {
    }

    public function getForm(?Setting $setting): Form
    {
        return new Form(
            $this->getFields($setting),
            $this->getButtons($setting),
        );
    }

    /**
     * @return array<string, AbstractParameter>
     */
    private function getFields(?Setting $setting): array
    {
        return [
            'systemModel' => (new AutoCompleteParameter('System Model', $this->modelAutoComplete))
                ->setValue($setting?->getValue()),
        ];
    }

    /**
     * @return array<string, Button>
     */
    private function getButtons(?Setting $setting): array
    {
        $parameters = [];
        $id = $setting?->getId();

        if ($id !== null) {
            $parameters['id'] = $id;
        }

        return [
            'save' => new Button(
                'Speichern',
                'marvin',
                'index',
                'settings',
                $parameters,
            ),
        ];
    }
}
