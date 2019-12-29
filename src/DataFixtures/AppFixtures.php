<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{


    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadMicroPosts($manager);
        $this->loadUsers($manager);
    }

    public function loadMicroPosts(ObjectManager $manager)
    {
        for($i=0; $i < 10 ; $i++)
        {
            $micro_post = new MicroPost();
            $micro_post->setText("Fixture text numero : ". rand(0,500));
            $micro_post->setTime( new \DateTime('2017-03-15 15:00:36'));
            $manager->persist($micro_post);
        }

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail("user_01@yahoo.fr");
        $user->setUsername("arsene");
        $user->setFullname('The User Arsene');
        $user->setPassword($this->passwordEncoder->encodePassword($user,'admin'));
        $manager->persist($user);

        $manager->flush();
    }
}
