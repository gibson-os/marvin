<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Model\Chat\Prompt;

use DateTime;
use GibsonOS\Core\Attribute\Install\Database\Column;
use GibsonOS\Core\Attribute\Install\Database\Constraint;
use GibsonOS\Core\Attribute\Install\Database\Table;
use GibsonOS\Core\Model\AbstractModel;
use GibsonOS\Core\Service\ParsedownService;
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

    #[Column(type: Column::TYPE_TEXT, collate: 'utf8mb4_unicode_ci', charset: 'utf8mb4')]
    private string $message = '';

    #[Column]
    private ?DateTime $startedAt = null;

    #[Column]
    private ?DateTime $doneAt = null;

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

    public function getStartedAt(): ?DateTime
    {
        return $this->startedAt;
    }

    public function setStartedAt(?DateTime $startedAt): Response
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getDoneAt(): ?DateTime
    {
        return $this->doneAt;
    }

    public function setDoneAt(?DateTime $doneAt): Response
    {
        $this->doneAt = $doneAt;

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
        $parsedown = new ParsedownService();
        $startedAt = $this->getStartedAt();
        $doneAt = $this->getDoneAt();
        $runtime = null;

        if ($startedAt !== null) {
            $runtime = ($doneAt ?? new DateTime())->getTimestamp() - $startedAt->getTimestamp();
        }

        return [
            'id' => $this->getId(),
            'message' => $parsedown->parse($this->getMessage()),
            'model' => $this->getModel(),
            'started' => $startedAt?->format('Y-m-d H:i:s'),
            'done' => $doneAt?->format('Y-m-d H:i:s'),
            'runtime' => $runtime,
        ];
    }
}
