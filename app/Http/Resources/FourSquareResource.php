<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FourSquareResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $new = collect([]);

        collect($this->resource->results)->each(function ($item, $key) use (&$new) {
            // Check if the $date key exists, if not initialize it as an array
            if (! isset($new[$key])) {
                $new[$key] = collect([]);
            }

            $new[$key]['name'] = $item?->name;
            $new[$key]['address'] = $item?->location?->address ?? $item?->location?->formatted_address;
            $new[$key]['formatted_address'] = $item?->location?->formatted_address;
            $new[$key]['city_name'] = $item?->location?->locality;
            $new[$key]['country_code'] = $item?->location?->country;
        });

        return [
            'search_results' => $new,
        ];
    }
}
