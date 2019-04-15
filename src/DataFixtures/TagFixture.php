<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Common\Persistence\ObjectManager;

class TagFixture extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_tags', function($count) use ($manager) {
            $tag = new Tag();
            $tag->setName($this->faker->sentence(1));

            return $tag;
        });

        $manager->flush();
    }
}
