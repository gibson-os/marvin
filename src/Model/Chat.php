<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Model;

use GibsonOS\Core\Attribute\Install\Database\Column;
use GibsonOS\Core\Attribute\Install\Database\Constraint;
use GibsonOS\Core\Attribute\Install\Database\Table;
use GibsonOS\Core\Model\AbstractModel;
use GibsonOS\Core\Model\User;
use GibsonOS\Module\Marvin\Model\Chat\Prompt;
use JsonSerializable;
use MDO\Enum\OrderDirection;

/**
 * @method Prompt[] getPrompts()
 * @method Chat     addPrompts(Prompt[] $prompts)
 * @method Chat     setPrompts(Prompt[] $prompts)
 * @method User     getUser()
 * @method Chat     setUser(User $user)
 */
#[Table]
class Chat extends AbstractModel implements JsonSerializable
{
    #[Column(attributes: [Column::ATTRIBUTE_UNSIGNED], autoIncrement: true)]
    private ?int $id = null;

    #[Column(length: 64)]
    private string $name;

    #[Column(attributes: [Column::ATTRIBUTE_UNSIGNED])]
    private ?int $userId = null;

    #[Constraint('chat', Prompt::class, orderBy: ['`createdAt`' => OrderDirection::ASC])]
    protected array $prompts;

    #[Constraint]
    protected User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Chat
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Chat
    {
        $this->name = $name;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): Chat
    {
        $this->userId = $userId;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }
}
