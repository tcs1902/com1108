<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\BookImage;
use App\Utils\UploaderHelper;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class BookFixture extends BaseFixture implements DependentFixtureInterface
{
    private static $bookImages = [
        'win_friends_1.jpg',
        'win_friends_2.jpg',
        'win_friends_3.jpg',
        'win_friends_4.jpg',
    ];
    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;

    /**
     * BookFixture constructor.
     * @param UploaderHelper $uploaderHelper
     */
    public function __construct(UploaderHelper $uploaderHelper)
    {
        $this->uploaderHelper = $uploaderHelper;
    }


    public function loadData(ObjectManager $manager)
    {
        $this->createMany(3, 'main_books', function($count) use ($manager) {
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


           foreach(self::$bookImages as $_) {
                $imageFilename = $this->fakeUploadImage();
                $bookImage = new BookImage($book);
                $bookImage->setFilename($imageFilename);
                $bookImage->setOriginalFilename($imageFilename);
                $bookImage->setMimeType('image/jpg');
                $manager->persist($bookImage);
            }


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

    private function fakeUploadImage(): string
    {
        $randomImage = $this->faker->randomElement(self::$bookImages);
        $fs = new Filesystem();
        $targetPath = sys_get_temp_dir().'/'.$randomImage;
        $fs->copy(__DIR__.'/bookImages/'.$randomImage, $targetPath, true);

        return $this->uploaderHelper->uploadBookImage(new File($targetPath));
    }
}
