<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixture extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_categories', function($count) use ($manager) {
            $category = new Category();
            $category->setName($this->faker->sentence($this->faker->randomElement([1,2])));

            return $category;
        });

        $manager->flush();
    }
}
