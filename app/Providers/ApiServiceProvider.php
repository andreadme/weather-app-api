<?php

namespace App\Providers;

use App\Services\Api\Contracts\FourSquareServiceInterface;
use App\Services\Api\Contracts\OpenWeatherServiceInterface;
use App\Services\Api\FourSquareApi;
use App\Services\Api\OpenWeatherApi;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $openWeatherUrl = env('OPEN_WEATHER_MAP_API_URL');
        $fourSquareUrl = env('FOUR_SQUARE_API_URL');
        $fourSquareApiKey = env('FOUR_SQUARE_API_KEY');

        $this->app->bind(OpenWeatherServiceInterface::class, function ($app) use ($openWeatherUrl) {
            return new OpenWeatherApi(new Client([
                'base_uri' => $openWeatherUrl,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]));
        });

        $this->app->bind(FourSquareServiceInterface::class, function ($app) use ($fourSquareUrl, $fourSquareApiKey) {
            return new FourSquareApi(new Client([
                'base_uri' => $fourSquareUrl,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $fourSquareApiKey,
                ],
            ]));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
