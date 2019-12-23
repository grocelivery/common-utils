<?php

namespace Grocelivery\Utils\Clients;

use Grocelivery\Utils\Exceptions\GeolocalizerClientException;
use Grocelivery\Utils\Exceptions\RestClientException;
use Grocelivery\Utils\Interfaces\JsonResponseInterface;
use Grocelivery\Utils\Interfaces\PropagatesAccessToken;

/**
 * Class GeolocalizerClient
 * @package Grocelivery\Utils\Clients
 */
class GeolocalizerClient extends RestClient implements PropagatesAccessToken
{
    /**
     * @param $method
     * @param string $path
     * @param array $options
     * @return JsonResponseInterface
     * @throws GeolocalizerClientException
     */
    public function request($method, $path = "", array $options = []): JsonResponseInterface
    {
        try {
            return parent::request($method, $path, $options);
        } catch (RestClientException $exception) {
            throw new GeolocalizerClientException();
        }
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return config('grocelivery.geolocalizer.host');
    }

    /**
     * @param string $type
     * @param string $name
     * @param array $location
     * @param array $payload
     * @return JsonResponseInterface
     */
    public function createPoint(string $type, string $name, array $location, array $payload = []): JsonResponseInterface
    {
        $this->setData([
            'name' => $name,
            'location' => $location,
            'payload' => $payload,
        ]);

        return $this->post("/points/$type");
    }

    /**
     * @param string $type
     * @param string $id
     * @return JsonResponseInterface
     */
    public function getPointById(string $type, string $id): JsonResponseInterface
    {
        return $this->get("/points/$type/$id");
    }

    /**
     * @param array $location
     * @return string
     * @throws GeolocalizerClientException
     */
    public function getPlaceByCoordinates(array $location): string
    {
        $this->setData([
            'latitude' => $location['latitude'],
            'longitude' => $location['longitude'],
        ]);

        $results = $this->get('/geocoding/reverse')->get('results');

        if (empty($results[0]['address'])) {
            throw new GeolocalizerClientException();
        }

        return $this->buildPlaceString($results[0]['address']);
    }

    /**
     * @param string $type
     * @param array $location
     * @param int $range
     * @return JsonResponseInterface
     */
    public function searchInRange(string $type, array $location, int $range): JsonResponseInterface
    {
        $this->setData([
            'latitude' => $location['latitude'],
            'longitude' => $location['longitude'],
            'kilometers' => $range,
        ]);

        return $this->get("/points/$type/search/range");
    }

    /**
     * @param array $address
     * @return mixed|string
     */
    protected function buildPlaceString(array $address)
    {
        $place = $address['city'];

        if (isset($address['city_district']) && $address['city'] !== $address['city_district']) {
            $place .= ', ' . $address['city_district'];
        }

        if (isset($address['neighbourhood'])) {
            $place .= ', ' . $address['neighbourhood'];
        }

        if (isset($address['road'])) {
            $place .= ', ' . $address['road'];
        }

        return $place;
    }
}