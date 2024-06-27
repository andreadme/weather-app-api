<?php

namespace App\Services\Api;

use App\Services\Api\Contracts\OpenWeatherServiceInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

/**
 * Class VIAAPI
 *
 * This class provides functionalities for interacting with the VIA flight API.
 * It allows for searching flights, reviewing flight details, and confirming flight blocks.
 */
class OpenWeatherApi implements OpenWeatherServiceInterface
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
    public function getWeatherForecast(string $cityName, string $countryCode, string $units = 'metric')
    {
        $apiKey = env('OPEN_WEATHER_MAP_API_KEY');
        $request = new Request('GET', "/data/2.5/forecast?q=$cityName,$countryCode&appid=$apiKey&units=$units", [], $this->body);

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
