<?php


namespace App\Controller;


use App\Entity\User;
use App\Event\UserRegisterEvent;
use App\Forms\UserType;
use App\Security\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrerController extends AbstractController
{
    public function __construct()
    {

    }

    /**
     * @Route("/register", name="user_register")
     */
    public function register(UserPasswordEncoderInterface $passwordEncoder, Request $request, EventDispatcherInterface $eventDispatcher, TokenGenerator $tokenGenerator)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $password  = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setConfirmationToken($tokenGenerator->getRandomSecureToken(30));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $userRegisterEvent = new UserRegisterEvent($user);

            /* Dispatch Event -> Dont forget to add Event name even if PHPSTORM blur the method parameter field */
            $eventDispatcher->dispatch($userRegisterEvent,UserRegisterEvent::NAME);

            return $this->redirectToRoute('security_login');
        }

        return $this->render('register/register.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /*
        - Every thing that can be happen to new User persisted in database should be handle by Events,
        - To dispatch an event we have to use a event name, and the event class
        - new UserRegisterEvent($user); --> Is created to hold any information that would be required by any subscriber
    */
}