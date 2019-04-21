<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function loadData(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setPhoneNumber($this->faker->phoneNumber);
        $user->setFirstName('Admin');
        $user->setPassword($this->encoder->encodePassword($user, 'abc123'));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $this->createMany(3, 'main_users', function($count) {
            $user = new User();
            $user->setEmail($this->faker->safeEmail);
            $user->setPhoneNumber($this->faker->phoneNumber);
            $user->setFirstName($this->faker->firstName);
            $user->setPassword($this->encoder->encodePassword($user, 'abc123'));

            return $user;
        });

        $manager->flush();
    }
}
