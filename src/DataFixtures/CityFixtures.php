<?php
/**
 * Created by PhpStorm.
 * User: korman
 * Date: 06.09.18
 * Time: 14:08
 */

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class CityFixtures
 * @package App\DataFixtures
 */
class CityFixtures extends Fixture
{
    /**
     * Put available cities to database.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $cities = $this->cities();

        foreach ($cities as $cityName) {
            $city = new City();
            $city->setName($cityName);

            $manager->persist($city);
        }

        $manager->flush();
    }

    /**
     * Return available cities.
     *
     * @return array
     */
    private function cities()
    {
        return [
            'Kiyv',
            'London',
            'Madrid'
        ];
    }
}