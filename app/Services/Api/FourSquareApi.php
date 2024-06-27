<?php

namespace App\Services\Api;

use App\Services\Api\Contracts\FourSquareServiceInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

/**
 * Class VIAAPI
 *
 * This class provides functionalities for interacting with the VIA flight API.
 * It allows for searching flights, reviewing flight details, and confirming flight blocks.
 */
class FourSquareApi implements FourSquareServiceInterface
{
    /**
     * @var string The body of the request.
     */
    private $body;

    /**
     * VIAAPI constructor.
     *
     * Initializes the VIAAPI service with a Guzzle HTTP client.
     *
     * @param  Client  $client  The Guzzle HTTP client used for API requests.
     */
    public function __construct(private Client $client) {}

    /**
     * Get the weather forecast.
     *
     * Sends a POST request to get the weather forecast based on the set parameters.
     *
     * @return object Returns the response from the Open Weather API as an object.
     */
    public function searchPlace(string $near)
    {
        $request = new Request('GET', "/v3/places/search?&near=$near&sort=DISTANCE", [], $this->body);

        return $this->sendRequest($request);
    }

    /**
     * Send the API request.
     *
     * Private helper method to send a request to the Open Weather API and process the response.
     *
     * @param  Request  $request  The request to send.
     * @return object Returns the decoded JSON response from the Open Weather API.
     */
    private function sendRequest(Request $request): object
    {
        $response = $this->client->sendAsync($request)->wait();

        return json_decode($response->getBody());
    }
}
