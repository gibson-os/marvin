<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Model;

use DateTime;
use GibsonOS\Core\Attribute\Install\Database\Column;
use GibsonOS\Core\Attribute\Install\Database\Constraint;
use GibsonOS\Core\Attribute\Install\Database\Table;
use GibsonOS\Core\Model\AbstractModel;
use GibsonOS\Core\Model\User;
use GibsonOS\Core\Wrapper\ModelWrapper;
use GibsonOS\Module\Marvin\Model\Chat\Prompt;
use JsonSerializable;
use MDO\Enum\OrderDirection;

/**
 * @method Prompt[]     getPrompts()
 * @method Chat         addPrompts(Prompt[] $prompts)
 * @method Chat         setPrompts(Prompt[] $prompts)
 * @method User         getUser()
 * @method Chat         setUser(User $user)
 * @method Chat\Model[] getModels()
 * @method Chat         setModels(Chat\Model[] $models)
 * @method Chat         addModels(Chat\Model[] $models)
 */
#[Table]
class Chat extends AbstractModel implements JsonSerializable
{
    #[Column(attributes: [Column::ATTRIBUTE_UNSIGNED], autoIncrement: true)]
    private ?int $id = null;

    #[Column(type: Column::TYPE_TEXT, collate: 'utf8mb4_unicode_ci', charset: 'utf8mb4', length: 64)]
    private string $name;

    #[Column]
    private DateTime $createdAt;

    #[Column]
    private bool $temporaryName = true;

    #[Column(attributes: [Column::ATTRIBUTE_UNSIGNED])]
    private ?int $userId = null;

    #[Constraint('chat', Prompt::class, orderBy: ['`created_at`' => OrderDirection::ASC])]
    protected array $prompts;

    #[Constraint]
    protected User $user;

    #[Constraint('chat', Chat\Model::class)]
    protected array $models;

    public function __construct(ModelWrapper $modelWrapper)
    {
        parent::__construct($modelWrapper);

        $this->setCreatedAt(new DateTime());
    }

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

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): Chat
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isTemporaryName(): bool
    {
        return $this->temporaryName;
    }

    public function setTemporaryName(bool $temporaryName): Chat
    {
        $this->temporaryName = $temporaryName;

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
            'createdAt' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'temporaryName' => $this->isTemporaryName(),
        ];
    }
}
