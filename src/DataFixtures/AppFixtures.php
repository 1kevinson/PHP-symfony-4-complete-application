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
        $random_a = rand(0,15) + 1;

        for($i=0; $i < 5 ; $i++)
        {
            $user = new User();
            $user->setEmail("user_email_me".$i.$random_a."@yahoo.fr");
            $user->setUsername("user_is_".$i.$random_a);
            $user->setFullname('The User Test');
            $user->setPassword($this->passwordEncoder->encodePassword($user,'admin'));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
