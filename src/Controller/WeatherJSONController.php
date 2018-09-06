<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\WeatherCache;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WeatherJSONController
 * @package App\Controller
 */
class WeatherJSONController extends AbstractController
{
    /**
     * Get data from openweathermap.org
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/weather/temperature", name="weather_temperature")
     */
    public function temperature(Request $request)
    {
        /**
         * @var City $city
         */
        $cityId = $request->get('city_id');
        //$appId = $this->getParameter('weathermapAppId');
        $appId = '3068aa669963cf174759890deef2616b';

        $em = $this->getDoctrine()->getManager();

        //try load from cache
        if ($data = $this->loadFromCache($cityId)) {
            $data = [
                'weather_data' => $data,
                'sys' => [
                    'url' => ''
                ]
            ];
        } elseif ($city = $em->getRepository(City::class)->find($cityId)) {

            $client = new Client();
            $response = $client->get('http://api.openweathermap.org/data/2.5/weather', [
                'query' => [
                    'q' => $city->getName(),
                    'APPID' => $appId
                ],
                'verify' => false
            ]);

            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody());

                //store to cache
                $this->storeToCache($cityId, $data);

                $data = [
                    'weather_data' => $data,
                    'sys' => [
                        'url' => 'http://api.openweathermap.org/data/2.5/weather?q=' . $city->getName() . '&appid=' . $appId
                    ]
                ];
            } else {

                $data = [
                    'error' => [
                        'code' => 1000,
                        'message' => 'Server return ' . $response->getStatusCode() . ' code'
                    ]
                ];
            }
        } else {
            $data = [
                'error' => [
                    'code' => 1001,
                    'message' => 'City ID ' . $cityId . ' not found in database'
                ]
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Get cities list.
     *
     * @Route("/weather/cities", name="weather_cities")
     */
    public function cities()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(City::class);

        $cities = $repository->findAll();

        $citiesArray = array_map(function($city){
            /**
             * @var City $city
             */

            return [
                'id'    => $city->getId(),
                'name'  => $city->getName()
            ];
        }, $cities);

        return new JsonResponse($citiesArray);
    }

    /**
     * Store to cache.
     *
     * @param $cityId
     * @param $data
     */
    private function storeToCache($cityId, $data)
    {
        $em = $this->getDoctrine()->getManager();
        $cache = new WeatherCache();
        $cache->setCityId($cityId);
        $cache->setData($data);
        $cache->setDate(new \DateTime());
        $em->persist($cache);
        $em->flush();
    }

    /**
     * Load from cache.
     *
     * @param $cityId
     * @return bool
     */
    private function loadFromCache($cityId)
    {
        /**
         * @var WeatherCache $cache
         */
        $em = $this->getDoctrine()->getManager();
        $cache = $em->getRepository(WeatherCache::class)->find($cityId);

        //check expired time
        if ($cache) {
            $currentDate = new \DateTime();
            $currentTs = $currentDate->getTimestamp();
            $cacheTs = $cache->getDate()->getTimestamp();

            //if cache have age more of 10 minutes
            if (($currentTs - $cacheTs)/60 > 10) {
                $em->remove($cache);
                $em->flush();

                return false;
            } else {
                return $cache->getData();
            }
        } else {
            return false;
        }

    }
}
