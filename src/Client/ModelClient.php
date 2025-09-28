<?php
declare(strict_types=1);

namespace GibsonOS\Module\Marvin\Client;

use Generator;
use GibsonOS\Core\Attribute\GetEnv;
use GibsonOS\Core\Dto\Web\Request;
use GibsonOS\Core\Enum\HttpMethod;
use GibsonOS\Core\Mapper\ModelMapper;
use GibsonOS\Core\Service\WebService;
use GibsonOS\Core\Utility\JsonUtility;
use GibsonOS\Module\Marvin\Model\Model;

class ModelClient
{
    public function __construct(
        private readonly WebService $webService,
        private readonly ModelMapper $modelMapper,
        #[GetEnv('OLLAMA_API_URL')]
        private readonly string $apiUrl,
        #[GetEnv('OLLAMA_API_PORT')]
        private readonly int $apiPort,
    ) {
    }

    /**
     * @return Generator<Model>
     */
    public function getModels(): Generator
    {
        $response = $this->webService->request(
            (new Request(sprintf('%s%s', $this->apiUrl, 'api/tags')))
                ->setPort($this->apiPort)
                ->setMethod(HttpMethod::GET),
        );
        $models = JsonUtility::decode($response->getBody()->getContent())['models'] ?? [];

        foreach ($models as &$model) {
            yield $this->modelMapper->mapToObject(Model::class, $model);
        }
    }
}
