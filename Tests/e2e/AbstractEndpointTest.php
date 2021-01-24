<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 24/01/21
 * Time: 16:08
 */

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
    public function getRequest(Client $client, string $url, int $length = 10)
    {
        $response = $client->request("GET", $url);

        return $response->getBody()->read($length);
    }

    /**
     * @param Client $client
     * @param string $url
     * @param int $length
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getJsonBody(Client $client, string $url, int $length = 8192)
    {
        return json_decode($this->getRequest($client, $url, $length), true);
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
