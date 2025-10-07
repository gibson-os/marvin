<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Controller;

use GibsonOS\Core\Attribute\CheckPermission;
use GibsonOS\Core\Attribute\GetSetting;
use GibsonOS\Core\Attribute\GetStore;
use GibsonOS\Core\Controller\AbstractController;
use GibsonOS\Core\Enum\Permission;
use GibsonOS\Core\Model\Setting;
use GibsonOS\Core\Model\User;
use GibsonOS\Core\Repository\ModuleRepository;
use GibsonOS\Core\Service\Response\AjaxResponse;
use GibsonOS\Core\Wrapper\ModelWrapper;
use GibsonOS\Module\Marvin\Form\SettingsForm;
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
        return $chatStore
            ->setUser($permissionUser)
            ->setLimit(0, 0)
            ->getAjaxResponse()
        ;
    }

    #[CheckPermission([Permission::READ])]
    public function getModels(
        #[GetStore]
        ModelStore $modelStore,
    ): AjaxResponse {
        return $modelStore
            ->setLimit(0, 0)
            ->getAjaxResponse()
        ;
    }

    #[CheckPermission([Permission::WRITE, Permission::MANAGE])]
    public function getSettingsForm(
        SettingsForm $settingsForm,
        #[GetSetting('systemModel', 'marvin')]
        ?Setting $defaultModelSetting,
    ): AjaxResponse {
        return $this->returnSuccess($settingsForm->getForm($defaultModelSetting));
    }

    #[CheckPermission([Permission::WRITE, Permission::MANAGE])]
    public function postSettings(
        ModelWrapper $modelWrapper,
        ModuleRepository $moduleRepository,
        int $systemModel,
        #[GetSetting('systemModel', 'marvin')]
        ?Setting $defaultModelSetting,
    ): AjaxResponse {
        if ($defaultModelSetting === null) {
            $defaultModelSetting = (new Setting($modelWrapper))
                ->setKey('systemModel')
                ->setModule($moduleRepository->getByName('marvin'))
            ;
        }

        $defaultModelSetting->setValue((string) $systemModel);
        $modelWrapper->getModelManager()->saveWithoutChildren($defaultModelSetting);

        return $this->returnSuccess();
    }
}
