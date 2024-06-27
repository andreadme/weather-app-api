<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowPlaceRequest;
use App\Services\FourSquareService;

class FourSquareController extends Controller
{
    public function __construct(protected FourSquareService $service) {}

    /**
     * Show the form for creating a new resource.
     */
    public function search(ShowPlaceRequest $request)
    {
        return $this->service->getPlaceDetails($request->validated());
    }
}
