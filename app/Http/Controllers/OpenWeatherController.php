<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowForecastRequest;
use App\Services\OpenWeatherService;

class OpenWeatherController extends Controller
{
    public function __construct(protected OpenWeatherService $service) {}

    /**
     * Show the form for creating a new resource.
     */
    public function index(ShowForecastRequest $request)
    {
        return $this->service->getForecast($request->validated());
    }
}
