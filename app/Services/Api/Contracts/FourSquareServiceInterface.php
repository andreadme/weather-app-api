<?php

namespace App\Services\Api\Contracts;

interface FourSquareServiceInterface
{
    /**
     * Get the weather forecast.
     *
     * Sends a POST request to get the weather forecast based on the set parameters.
     *
     * @return object Returns the response from the Open Weather API as an object.
     */
    public function searchPlace(string $near);
}
