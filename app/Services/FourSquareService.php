<?php

namespace App\Services;

use App\Http\Resources\FourSquareResource;
use App\Services\Api\Contracts\FourSquareServiceInterface;
use App\Traits\APIResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * FourSquareService Class
 * Handles weather-related operations.
 */
class FourSquareService
{
    use APIResponseTrait;

    public function __construct(protected FourSquareServiceInterface $fourSquare) {}

    public function getPlaceDetails(array $payload): JsonResponse
    {
        // Get the place details
        $place = $this->fourSquare->searchPlace($payload['near']);

        $formatted = FourSquareResource::make($place)->resolve();

        return $this->success('Successfully fetch', $formatted, Response::HTTP_OK);
    }
}
