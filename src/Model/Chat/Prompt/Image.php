<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Model\Chat\Prompt;

use GibsonOS\Core\Attribute\Install\Database\Column;
use GibsonOS\Core\Attribute\Install\Database\Constraint;
use GibsonOS\Core\Attribute\Install\Database\Table;
use GibsonOS\Core\Model\AbstractModel;
use GibsonOS\Module\Marvin\Model\Chat\Prompt;
use JsonSerializable;

/**
 * @method Prompt getPrompt()
 * @method Image  setPrompt(Prompt $prompt)
 */
#[Table]
class Image extends AbstractModel implements JsonSerializable
{
    #[Column(attributes: [Column::ATTRIBUTE_UNSIGNED], autoIncrement: true)]
    private ?int $id = null;

    #[Column(length: 64)]
    private string $name;

    #[Column(length: 256)]
    private string $path;

    #[Column(attributes: [Column::ATTRIBUTE_UNSIGNED])]
    private int $promptId;

    #[Constraint]
    protected Prompt $prompt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Image
    {
        $this->id = $id;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): Image
    {
        $this->path = $path;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Image
    {
        $this->name = $name;

        return $this;
    }

    public function getPromptId(): int
    {
        return $this->promptId;
    }

    public function setPromptId(int $promptId): Image
    {
        $this->promptId = $promptId;

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
