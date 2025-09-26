<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Model;

use GibsonOS\Core\Attribute\Install\Database\Column;
use GibsonOS\Core\Attribute\Install\Database\Constraint;
use GibsonOS\Core\Attribute\Install\Database\Table;
use GibsonOS\Core\Model\AbstractModel;
use GibsonOS\Module\Marvin\Model\Chat\Prompt;
use JsonSerializable;
use MDO\Enum\OrderDirection;

/**
 * @method Prompt[] getPrompts()
 * @method Chat     addPrompts(Prompt[] $prompts)
 * @method Chat     setPrompts(Prompt[] $prompts)
 */
#[Table]
class Chat extends AbstractModel implements JsonSerializable
{
    #[Column(attributes: [Column::ATTRIBUTE_UNSIGNED], autoIncrement: true)]
    private ?int $id = null;

    #[Constraint('chat', Prompt::class, orderBy: ['`createdAt`' => OrderDirection::ASC])]
    protected array $prompts;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Chat
    {
        $this->id = $id;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'prompts' => $this->getPrompts(),
        ];
    }
}
