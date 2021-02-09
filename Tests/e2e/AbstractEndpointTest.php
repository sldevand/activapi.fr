<?php

namespace Tests\e2e;

use GuzzleHttp\Client;

/**
 * Class AbstractEndpointTest
 * @package Tests\e2e
 */
abstract class AbstractEndpointTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param Client $client
     * @param string $url
     * @param int $length
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRequest(Client $client, string $url, int $length = 64000)
    {
        $response = $client->request("GET", $url);

        return $response->getBody()->read($length);
    }

    /**
     * @param Client $client
     * @param string $url
     * @param array $body
     * @param int $length
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function postRequest(Client $client, string $url, array $body, int $length = 64000)
    {
        $response = $client->post($url, ['form_params' => $body]);

        return $response->getBody()->read($length);
    }

    /**
     * @param Client $client
     * @param string $url
     * @param array $body
     * @param int $length
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function putRequest(Client $client, string $url, array $body, int $length = 64000)
    {
        $response = $client->put($url, ['form_params' => $body]);

        return $response->getBody()->read($length);
    }

    /**
     * @param Client $client
     * @param string $url
     * @param array $body
     * @param int $length
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteRequest(Client $client, string $url, array $body, int $length = 64000)
    {
        $response = $client->delete($url, ['form_params' => $body]);

        return $response->getBody()->read($length);
    }

    /**
     * @param Client $client
     * @param string $url
     * @param int $length
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getJsonBody(Client $client, string $url, int $length = 64000)
    {
        return json_decode($this->getRequest($client, $url, $length), true);
    }

    /**
     * @param Client $client
     * @param string $url
     * @param array $body
     * @param int $length
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPostJsonBody(Client $client, string $url,array $body, int $length = 64000)
    {
        return json_decode($this->postRequest($client, $url, $body, $length), true);
    }

    /**
     * @param Client $client
     * @param string $url
     * @param array $body
     * @param int $length
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPutJsonBody(Client $client, string $url,array $body, int $length = 64000)
    {
        return json_decode($this->putRequest($client, $url, $body, $length), true);
    }

    /**
     * @param Client $client
     * @param string $url
     * @param array $body
     * @param int $length
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDeleteJsonBody(Client $client, string $url,array $body, int $length = 64000)
    {
        return json_decode($this->deleteRequest($client, $url, $body, $length), true);
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getFullUrl(string $path): string
    {
        $baseUrl = $_ENV['TEST_BASE_URL'] ?? $_ENV['BASE_URL'];
        $rootApiUri = $_ENV['TEST_ROOT_API_URI'] ?? $_ENV['ROOT_API_URI'];

        return $baseUrl . $rootApiUri . $path;
    }
}
