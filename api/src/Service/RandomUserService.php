<?php

namespace App\Service;

use Doctrine\DBAL\Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RandomUserService
{
    public function __construct(
        private HttpClientInterface $client,
        private ParameterBagInterface $parameterBag
    ) {}

    public function fetchUsers(?int $resultCount): array
    {
        $baseUrl = $this->parameterBag->get('random_user_api_url');
        $minResults = $resultCount ?: $this->parameterBag->get('random_user_api_min_results');
        $seed = $this->parameterBag->get('random_user_api_seed');
        $nationality = $this->parameterBag->get('random_user_api_nationality');
        $randomUserApiUrl = $baseUrl . '?results=' . $minResults . '&seed=' . $seed . '&nat=' . $nationality;
        $response = $this->client->request('GET', $randomUserApiUrl);

        if (200 !== $response->getStatusCode()) {
            throw new NotFoundHttpException('Unable to fetch users from the 3rd party API');
        }

        return $response->toArray()['results'];
    }
}
