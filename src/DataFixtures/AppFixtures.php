<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserPreferences;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private const USERS = [
        [
            'username'  => 'john_doe',
            'email'     => 'john_doe@doe.com',
            'password'  => 'john123',
            'fullName'  => 'John Doe',
            'roles'     => [User::ROLE_USER]
        ],
        [
            'username' => 'rob_smith',
            'email' => 'rob_smith@smith.com',
            'password' => 'rob12345',
            'fullName' => 'Rob Smith',
            'roles'     => [User::ROLE_USER]
        ],
        [
            'username' => 'marry_gold',
            'email' => 'marry_gold@gold.com',
            'password' => 'marry12345',
            'fullName' => 'Marry Gold',
            'roles'     => [User::ROLE_USER]
        ],
        [
            'username' => 'super_admin',
            'email' => 'super_admin@admin.com',
            'password' => 'admin123',
            'fullName' => 'Sudo User',
            'roles'     => [User::ROLE_ADMIN]
        ],
        [
            'username' => '1kevinson',
            'email' => '1kevinson@admin.com',
            'password' => 'admin',
            'fullName' => 'Arsene Kevin',
            'roles'     => [User::ROLE_ADMIN]
        ]
    ];

    private const POST_TEXT = [
        'Hello, how are you?',
        'It\'s nice sunny weather today',
        'I need to buy some ice cream!',
        'I wanna buy a new car',
        'There\'s a problem with my phone',
        'I need to go to the doctor',
        'What are you up to today?',
        'Did you watch the game yesterday?',
        'How was your day?'
    ];

    private const LANGAGES = [
      'fr',
      'en'
    ];

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadMicroPosts($manager);
    }

    public function loadMicroPosts(ObjectManager $manager)
    {
        for($i=0; $i < 150; $i++)
        {
            $micro_post = new MicroPost();
            $micro_post->setText(
                self::POST_TEXT[rand(0, count(self::POST_TEXT) - 1 )]
            );

            $date = new \DateTime();
            $date->modify('-'. rand(0,10) .' day');
            $micro_post->setTime( $date);
            $micro_post->setUser($this->getReference(
                self::USERS[rand(0,count(self::USERS) - 1)]['username']
            ));

            $manager->persist($micro_post);
        }

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
    {
        foreach (self::USERS as $userData)
        {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setUsername($userData['username']);
            $user->setFullname($userData['fullName']);
            $user->setPassword($this->passwordEncoder->encodePassword($user,$userData['password']));
            $user->setRoles($userData['roles']);
            $user->setEnabled(true);

            $this->addReference($userData['username'],$user);

            $preference = new UserPreferences();
            $preference->setLocale(self::LANGAGES[rand(0,1)]);

            $user->setPreferences($preference);
            $manager->persist($user);
         }
            $manager->flush();
    }


    /*
     * We have to set micropost fixture first because we would like to retrieve his reference first
     *
        addReference and getReference and  are use to link 2 Entities *
        fix reference to username with [addReference] and  associate micropost randomly with [getReference]
    */
}
