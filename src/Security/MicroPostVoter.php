<?php


namespace App\Security;


use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/* Is_granted() function automatically verify voter in security folder, the 2 methods supports, voteOnAttribute must return true */

class MicroPostVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    private $decisionManager;


    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {

        $this->decisionManager = $decisionManager;
    }

    /*
     * Support is use to check granted access for the connected user in twig template is_granted('edit', post)
     *
     * */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT, self::DELETE]))
        {
            return false;
        }

        if(!$subject instanceof MicroPost)
        {
            return false;
        }

        return true;

    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        //Check if the current user has the ROLE_ADMIN to OVERRIDE ALL RIGHTS that Voters provide
        if($this->decisionManager->decide($token, [User::ROLE_ADMIN]))
        {
            return true;
        }

        //Recuperer l'utilisateur courant
        $authenticatedUser = $token->getUser();

        if(!$authenticatedUser instanceof User)
        {
            return false;
        }

        /** @var MicroPost $micropost */
        $micropost = $subject;

         return $micropost->getUser()->getId() === $authenticatedUser->getId();
    }

    /*
     * How Symfony Uses Voters¶
        In order to use voters, you have to understand how Symfony works with them.
        All voters are called each time you use the isGranted() method on Symfony's authorization checker or call denyAccessUnlessGranted()
        in a controller (which uses the authorization checker), or by access controls.

        Ultimately, Symfony takes the responses from all voters and makes the final decision (to allow or deny access to the resource) according to the strategy defined in the application,
        which can be: affirmative, consensus or unanimous.

        /** @var MicroPost $micropost  --> est utilisé pour recuperer les méthodes de l'entité correspondante ( MicroPost)
         To search for a specific service, re-run this command with a search term. (e.g. php bin/console debug:container log)

     * */
}

