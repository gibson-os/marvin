<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Model\Chat;

use GibsonOS\Core\Attribute\Install\Database\Column;
use GibsonOS\Core\Attribute\Install\Database\Constraint;
use GibsonOS\Core\Attribute\Install\Database\Table;
use GibsonOS\Core\Model\AbstractModel;
use GibsonOS\Module\Marvin\Model\Chat;
use GibsonOS\Module\Marvin\Model\Model as AiModel;

/**
 * @method Chat    getChat()
 * @method Model   setChat(Chat $chat)
 * @method AiModel getModel()
 * @method Model   setModel(AiModel $model)
 */
#[Table]
class Model extends AbstractModel
{
    #[Column(attributes: [Column::ATTRIBUTE_UNSIGNED], autoIncrement: true)]
    private ?int $id = null;

    #[Column(attributes: [Column::ATTRIBUTE_UNSIGNED])]
    private int $chatId;

    #[Column(attributes: [Column::ATTRIBUTE_UNSIGNED])]
    private int $modelId;

    #[Constraint]
    protected Chat $chat;

    #[Constraint]
    protected AiModel $model;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Model
    {
        $this->id = $id;

        return $this;
    }

    public function getChatId(): int
    {
        return $this->chatId;
    }

    public function setChatId(int $chatId): Model
    {
        $this->chatId = $chatId;

        return $this;
    }

    public function getModelId(): int
    {
        return $this->modelId;
    }

    public function setModelId(int $modelId): Model
    {
        $this->modelId = $modelId;

        return $this;
    }
}
