<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Controller;

use GibsonOS\Core\Attribute\CheckPermission;
use GibsonOS\Core\Attribute\GetStore;
use GibsonOS\Core\Controller\AbstractController;
use GibsonOS\Core\Enum\Permission;
use GibsonOS\Core\Model\User;
use GibsonOS\Core\Service\Response\AjaxResponse;
use GibsonOS\Module\Marvin\Store\ChatStore;
use GibsonOS\Module\Marvin\Store\ModelStore;

class IndexController extends AbstractController
{
    #[CheckPermission([Permission::READ])]
    public function getChats(
        User $permissionUser,
        #[GetStore]
        ChatStore $chatStore,
    ): AjaxResponse {
        $chatStore->setUser($permissionUser);

        return $chatStore->getAjaxResponse();
    }

    #[CheckPermission([Permission::READ])]
    public function getModels(
        #[GetStore]
        ModelStore $modelStore,
    ): AjaxResponse {
        return $modelStore->getAjaxResponse();
    }
}
