<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Controller;

use DateMalformedStringException;
use DateTimeImmutable;
use Exception;
use GibsonOS\Core\Attribute\CheckPermission;
use GibsonOS\Core\Attribute\GetMappedModel;
use GibsonOS\Core\Attribute\GetModel;
use GibsonOS\Core\Attribute\GetSetting;
use GibsonOS\Core\Attribute\GetStore;
use GibsonOS\Core\Controller\AbstractController;
use GibsonOS\Core\Enum\Permission;
use GibsonOS\Core\Exception\Model\SaveError;
use GibsonOS\Core\Model\Setting;
use GibsonOS\Core\Model\User;
use GibsonOS\Core\Service\FileService;
use GibsonOS\Core\Service\Response\AjaxResponse;
use GibsonOS\Core\Service\Response\FileResponse;
use GibsonOS\Core\Wrapper\ModelWrapper;
use GibsonOS\Module\Marvin\Model\Chat;
use GibsonOS\Module\Marvin\Model\Chat\Model;
use GibsonOS\Module\Marvin\Model\Chat\Prompt;
use GibsonOS\Module\Marvin\Model\Chat\Prompt\Image;
use GibsonOS\Module\Marvin\Service\ChatService;
use GibsonOS\Module\Marvin\Store\Chat\PromptStore;
use JsonException;
use MDO\Client;
use MDO\Exception\ClientException;
use MDO\Exception\RecordException;
use ReflectionException;

class ChatController extends AbstractController
{
    #[CheckPermission([Permission::READ])]
    public function get(
        #[GetModel]
        Chat $chat,
    ): AjaxResponse {
        $data = $chat->jsonSerialize();
        $data['models'] = array_map(
            static fn (Model $model): array => $model->getModel()->jsonSerialize(),
            $chat->getModels(),
        );

        return $this->returnSuccess($data);
    }

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

    /**
     * @throws SaveError
     * @throws JsonException
     * @throws RecordException
     * @throws ReflectionException
     * @throws ClientException
     * @throws DateMalformedStringException
     */
    #[CheckPermission([Permission::WRITE])]
    public function postPrompt(
        User $permissionUser,
        ModelWrapper $modelWrapper,
        ChatService $chatService,
        FileService $fileService,
        Client $client,
        #[GetSetting('filePath', 'marvin')]
        Setting $filePath,
        #[GetMappedModel(['id' => 'chatId'], ['id' => 'chatId'])]
        Chat $chat,
        #[GetMappedModel]
        Prompt $prompt,
        array $files = [],
    ): AjaxResponse {
        $client->startTransaction();

        try {
            if ($chat->getId() === 0) {
                $chat
                    ->setUser($permissionUser)
                    ->setName(sprintf('Chat vom %s', (new DateTimeImmutable())->format('d-m-Y H:i:s')))
                ;
                $prompt->setChat($chat);
            }

            $modelManager = $modelWrapper->getModelManager();

            $modelManager->save($chat);
            $chatService->addMissingPrompts($chat);

            foreach ($files as $file) {
                $filename = sprintf('%s%s', $filePath->getValue(), uniqid());
                $prompt->addImages([
                    (new Image($modelWrapper))
                        ->setName($file['name'])
                        ->setPath($filename),
                ]);
                $fileService->move($file['tmp_name'], $filename);
            }

            $chatService->addPromptResponses($chat, $prompt);
            $modelManager->save($prompt);
            $client->commit();
        } catch (Exception $exception) {
            $client->rollBack();

            throw $exception;
        }

        return $this->returnSuccess($chat);
    }

    #[CheckPermission([Permission::READ])]
    public function getImage(
        #[GetModel]
        Image $image,
    ): FileResponse {
        return new FileResponse($this->requestService, $image->getPath());
    }
}
