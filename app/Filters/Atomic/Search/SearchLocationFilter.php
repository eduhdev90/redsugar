<?php

namespace App\Filters\Atomic\Search;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class SearchLocationFilter
{
    public function __invoke(Builder $query, int|string $value)
    {
        if ($value > 500) return $query;
        $userAuthLocation = $this->authenticatedUserLocation();

        if (!$userAuthLocation) return $query;

        $location = $this->calcLatLng($value, $userAuthLocation['latitude'], $userAuthLocation['longitude']);

        return $query
            ->where('latitude', '>=', $location['minLat'])
            ->where('latitude', '<=', $location['maxLat'])
            ->where('longitude', '>=', $location['minLng'])
            ->where('longitude', '<=', $location['maxLng']);
    }

    private function authenticatedUserLocation(): bool|array
    {
        $profile = auth()->user()->profile_available()->select('latitude', 'longitude')->first();

        if (!$profile) {
            return false;
        }

        if ($profile->latitude === null || $profile->longitude === null) return false;

        return [
            'latitude' => $profile->latitude,
            'longitude' => $profile->longitude,
        ];
    }

    private function calcLatLng($distance, $userLat, $userLng, $unit = 'km'): array
    {
        $earthRadius = 6371; // earth radius in KM
        $relationEarthDistance = $distance / $earthRadius;

        return [
            'maxLat' => $userLat + rad2deg($relationEarthDistance),
            'minLat' => $userLat - rad2deg($relationEarthDistance),
            'maxLng' => $userLng + rad2deg(asin($relationEarthDistance) / cos(deg2rad($userLat))),
            'minLng' => $userLng - rad2deg(asin($relationEarthDistance) / cos(deg2rad($userLat)))
        ];
    }
}
