<?php


namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FollowingController
 * @package App\Controller
 * @Security("is_granted('ROLE_USER')")
 * @Route("/following")
 */
class FollowingController extends AbstractController
{
    #region Properties
    private $entityManager;
    private $flashBag;
    #endregion

    #region Contructor

    public function __construct(EntityManagerInterface $entityManager, FlashBagInterface $flashBag)
    {
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
    }
    #endregion

    #region Methods
    /**
     * @Route("/follow/{id}", name="following_follow")
     */
    public function follow(User $userToFollow)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        //In case of Already following the User
        if($currentUser->getFollowing()->contains($userToFollow))
        {
            $this->flashBag->add('exception', 'You already follow -> '. $userToFollow->getFullname() );

            return $this->redirectToRoute('micro_post_user', [
                'username' => $userToFollow->getUsername()
            ]);
        }

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
    #endregion
}