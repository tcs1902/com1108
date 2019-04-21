<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class BookFixture extends BaseFixture implements DependentFixtureInterface
{

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(3, 'main_books', function($count) {
           $book = new Book();
           $book->setName($this->faker->sentence($this->faker->numberBetween(1, 6)));
           $book->setAuthor(sprintf('%s %s', $this->faker->firstName, $this->faker->lastName));
           $book->setDescription($this->faker->paragraph);
           $book->setIsbn($this->faker->isbn13);
           $categories = $this->getRandomReferences('main_categories', $this->faker->numberBetween(0, 2));
           foreach($categories as $category) {
               $book->addCategory($category);
           }
           $tags = $this->getRandomReferences('main_tags', $this->faker->numberBetween(0, 5));
           foreach ($tags as $tag) {
               $book->addTag($tag);
           }

           $stock = $this->faker->numberBetween(0, 10);
           $book->setStock($stock);
           $book->setSold($this->faker->numberBetween(0, $stock));

           $owner = $this->getRandomReference('main_users');
           $book->setOwner($owner);

           return $book;
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TagFixture::class,
            CategoryFixture::class,
            UserFixture::class,
        ];
    }
}
