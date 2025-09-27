<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Model\Chat\Prompt;

use GibsonOS\Core\Attribute\Install\Database\Column;
use GibsonOS\Core\Attribute\Install\Database\Constraint;
use GibsonOS\Core\Attribute\Install\Database\Table;
use GibsonOS\Core\Model\AbstractModel;
use GibsonOS\Module\Marvin\Model\Chat\Prompt;
use GibsonOS\Module\Marvin\Model\Model;
use JsonSerializable;

/**
 * @method Prompt   getPrompt()
 * @method Response setPrompt(Prompt $prompt)
 * @method Model    getModel()
 * @method Response setModel(Model $model)
 */
#[Table]
class Response extends AbstractModel implements JsonSerializable
{
    #[Column(attributes: [Column::ATTRIBUTE_UNSIGNED], autoIncrement: true)]
    private ?int $id = null;

    #[Column(type: Column::TYPE_TEXT)]
    private string $message = '';

    #[Column]
    private bool $done = false;

    #[Column(attributes: [Column::ATTRIBUTE_UNSIGNED])]
    private int $promptId;

    #[Column(attributes: [Column::ATTRIBUTE_UNSIGNED])]
    private int $modelId;

    #[Constraint]
    protected Prompt $prompt;

    #[Constraint]
    protected Model $model;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Response
    {
        $this->id = $id;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): Response
    {
        $this->message = $message;

        return $this;
    }

    public function isDone(): bool
    {
        return $this->done;
    }

    public function setDone(bool $done): Response
    {
        $this->done = $done;

        return $this;
    }

    public function getPromptId(): int
    {
        return $this->promptId;
    }

    public function setPromptId(int $promptId): Response
    {
        $this->promptId = $promptId;

        return $this;
    }

    public function getModelId(): int
    {
        return $this->modelId;
    }

    public function setModelId(int $modelId): Response
    {
        $this->modelId = $modelId;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [];
    }
}
