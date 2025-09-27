<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Controller;

use GibsonOS\Core\Attribute\CheckPermission;
use GibsonOS\Core\Attribute\GetModel;
use GibsonOS\Core\Attribute\GetStore;
use GibsonOS\Core\Controller\AbstractController;
use GibsonOS\Core\Enum\Permission;
use GibsonOS\Core\Service\Response\AjaxResponse;
use GibsonOS\Module\Marvin\Model\Chat;
use GibsonOS\Module\Marvin\Store\Chat\PromptStore;

class ChatController extends AbstractController
{
    #[CheckPermission([Permission::READ])]
    public function getPrompts(
        #[GetModel]
        Chat $chat,
        #[GetStore]
        PromptStore $promptStore,
    ): AjaxResponse {
        $promptStore->setChat($chat);

        return $promptStore->getAjaxResponse();
    }
}
