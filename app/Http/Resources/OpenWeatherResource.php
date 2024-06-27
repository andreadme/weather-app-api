<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OpenWeatherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $current = collect([]);
        $future = collect([]);

        collect($this->list)->each(function ($item) use ($request, &$current, &$future) {
            $isFirst = false;
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $item->dt_txt)->format('Y-m-d');
            $dateToday = Carbon::now()->format('Y-m-d');
            $time = Carbon::createFromFormat('Y-m-d H:i:s', $item->dt_txt)->format('H:i');
            $formatted = Carbon::createFromFormat('Y-m-d H:i:s', $item->dt_txt)->format('D, M j, H:i');

            $dateObj = Carbon::createFromFormat('Y-m-d', $date);
            $dateTodayObj = Carbon::createFromFormat('Y-m-d', $dateToday);

            $descriptions = ucfirst(collect($item->weather)->pluck('description')->join('. '));

            if ($dateTodayObj->equalTo($dateObj)) {
                // Check if the $date key exists, if not initialize it as an array
                if (! isset($current[$date.';'.$time])) {
                    $current[$date.';'.$time] = collect([]);
                    $isFirst = true;
                }

                $current[$date.';'.$time]['display_date'] = $formatted;
                $current[$date.';'.$time]['description'] = $descriptions;
                $current[$date.';'.$time]['feels_like'] = (int) $item->main->feels_like.$this->getTemperatureUnit($request['units']);
                $current[$date.';'.$time]['main_temp'] = (int) $item->main->temp.$this->getTemperatureUnit($request['units']);
                $current[$date.';'.$time]['min_temp'] = (int) $item->main->temp_min.$this->getTemperatureUnit($request['units']);
                $current[$date.';'.$time]['max_temp'] = (int) $item->main->temp_max.$this->getTemperatureUnit($request['units']);
                $current[$date.';'.$time]['wind'] = $item->wind->speed.' '.$this->getWindSpeedUnit($request['units']).' '.$this->getWindDirection($item->wind->deg);
                $current[$date.';'.$time]['humidity'] = $item->main->humidity.'%';
                $current[$date.';'.$time]['icon'] = collect($item->weather)->pluck('icon')->first();
                $current[$date.';'.$time]['date'] = $date;
                $current[$date.';'.$time]['time'] = $time;

                if ($isFirst) {
                    $combined = $current;
                }
            } elseif ($dateTodayObj->lessThan($dateObj)) {
                if (! isset($future[$date.';'.$time])) {
                    $future[$date.';'.$time] = collect([]);
                }

                $future[$date.';'.$time]['display_date'] = $formatted;
                $future[$date.';'.$time]['description'] = $descriptions;
                $future[$date.';'.$time]['feels_like'] = (int) $item->main->feels_like.$this->getTemperatureUnit($request['units']);
                $future[$date.';'.$time]['main_temp'] = (int) $item->main->temp.$this->getTemperatureUnit($request['units']);
                $future[$date.';'.$time]['min_temp'] = (int) $item->main->temp_min.$this->getTemperatureUnit($request['units']);
                $future[$date.';'.$time]['max_temp'] = (int) $item->main->temp_max.$this->getTemperatureUnit($request['units']);
                $future[$date.';'.$time]['wind'] = $item->wind->speed.' '.$this->getWindSpeedUnit($request['units']).' '.$this->getWindDirection($item->wind->deg);
                $future[$date.';'.$time]['humidity'] = $item->main->humidity.'%';
                $future[$date.';'.$time]['icon'] = collect($item->weather)->pluck('icon')->first();
                $future[$date.';'.$time]['date'] = $date;
                $future[$date.';'.$time]['time'] = $time;
            }
        });

        return [
            'city' => $this?->city?->name,
            'country' => $this?->city?->country,
            'current_weather' => $current->toArray(),
            'future_weather' => $future->toArray(),
            'combined' => $current->merge($future)->toArray(),
        ];
    }

    private function getWindDirection($degrees)
    {
        $directions = ['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW', 'N'];
        $index = intval(($degrees + 22.5) / 45);

        return $directions[$index % 8];
    }

    private function getTemperatureUnit($units)
    {
        return $units === 'imperial' ? '°F' : ($units === 'metric' ? '°C' : 'K');
    }

    private function getWindSpeedUnit($units)
    {
        return $units === 'imperial' ? 'miles/hour' : 'meter/sec';
    }
}
