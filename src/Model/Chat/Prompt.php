<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Model\Chat;

use DateTime;
use GibsonOS\Core\Attribute\Install\Database\Column;
use GibsonOS\Core\Attribute\Install\Database\Constraint;
use GibsonOS\Core\Attribute\Install\Database\Table;
use GibsonOS\Core\Model\AbstractModel;
use GibsonOS\Core\Wrapper\ModelWrapper;
use GibsonOS\Module\Marvin\Enum\Role;
use GibsonOS\Module\Marvin\Model\Chat;
use GibsonOS\Module\Marvin\Model\Chat\Prompt\Image;
use GibsonOS\Module\Marvin\Model\Chat\Prompt\Response;
use JsonSerializable;

/**
 * @method Chat       getChat()
 * @method Prompt     setChat(Chat $chat)
 * @method Response[] getResponses()
 * @method Prompt     setResponses(Response[] $messages)
 * @method Prompt     addResponses(Response[] $messages)
 * @method Image[]    getImages()
 * @method Prompt     setImages(Image[] $messages)
 * @method Prompt     addImages(Image[] $messages)
 */
#[Table]
class Prompt extends AbstractModel implements JsonSerializable
{
    #[Column(attributes: [Column::ATTRIBUTE_UNSIGNED], autoIncrement: true)]
    private ?int $id = null;

    #[Column(type: Column::TYPE_TEXT)]
    private string $prompt;

    #[Column]
    private DateTime $createdAt;

    #[Column]
    private Role $role = Role::USER;

    #[Column(attributes: [Column::ATTRIBUTE_UNSIGNED])]
    private int $chatId;

    #[Constraint]
    protected Chat $chat;

    #[Constraint('prompt', Image::class)]
    protected array $images;

    #[Constraint('prompt', Response::class)]
    protected array $responses;

    public function __construct(ModelWrapper $modelWrapper)
    {
        parent::__construct($modelWrapper);

        $this->setCreatedAt(new DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Prompt
    {
        $this->id = $id;

        return $this;
    }

    public function getPrompt(): string
    {
        return $this->prompt;
    }

    public function setPrompt(string $prompt): Prompt
    {
        $this->prompt = $prompt;

        return $this;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setRole(Role $role): Prompt
    {
        $this->role = $role;

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): Prompt
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getChatId(): int
    {
        return $this->chatId;
    }

    public function setChatId(int $chatId): Prompt
    {
        $this->chatId = $chatId;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'prompt' => $this->getPrompt(),
            'responses' => $this->getResponses(),
        ];
    }
}
