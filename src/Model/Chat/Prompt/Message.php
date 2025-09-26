<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Model\Chat\Prompt;

use GibsonOS\Core\Attribute\Install\Database\Column;
use GibsonOS\Core\Attribute\Install\Database\Table;
use GibsonOS\Core\Model\AbstractModel;
use JsonSerializable;

#[Table]
class Message extends AbstractModel implements JsonSerializable
{
    #[Column(attributes: [Column::ATTRIBUTE_UNSIGNED], autoIncrement: true)]
    private ?int $id = null;

    public function jsonSerialize(): array
    {
        return [];
    }
}
