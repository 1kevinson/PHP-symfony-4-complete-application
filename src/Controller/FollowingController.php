<?php


namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FollowingController
 * @package App\Controller
 * @Security("is_granted('ROLE_USER')")
 * @Route("/following")
 */
class FollowingController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/follow/{id}", name="following_follow")
     */
    public function follow(User $userToFollow)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if( $userToFollow->getId() !== $currentUser->getId() )
        {
            $currentUser->getFollowing()->add($userToFollow);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('micro_post_user', ['username' => $userToFollow->getUsername()]);

    }

    /**
     * @Route("/unfollow/{id}", name="following_unfollow")
     */
    public function unfollow(User $userToUnfollow)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $currentUser->getFollowing()->removeElement($userToUnfollow);

        $this->entityManager->flush();

        return $this->redirectToRoute('micro_post_user', ['username' => $userToUnfollow->getUsername()]);
    }
}