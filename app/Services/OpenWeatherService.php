<?php

namespace App\Services;

use App\Http\Resources\OpenWeatherResource;
use App\Services\Api\Contracts\OpenWeatherServiceInterface;
use App\Traits\APIResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * OpenWeatherService Class
 * Handles weather-related operations.
 */
class OpenWeatherService
{
    use APIResponseTrait;

    public function __construct(protected OpenWeatherServiceInterface $openWeather) {}

    public function getForecast(array $payload): JsonResponse
    {
        // Get the weather forecast
        $forecast = $this->openWeather->getWeatherForecast($payload['city_name'], $payload['country_code'], $payload['units']);

        $formatted = OpenWeatherResource::make($forecast)->resolve();

        return $this->success('Successfully fetch', $formatted, Response::HTTP_OK);
    }
}
