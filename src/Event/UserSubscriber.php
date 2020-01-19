<?php


namespace App\Event;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment as Twig_Environment;

class UserSubscriber implements EventSubscriberInterface
{

    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /* Override the Event Const Name to pass Subscriber name Method with getSubscribedEvents */
    public static function getSubscribedEvents()
    {
        return [
            UserRegisterEvent::NAME => 'onUserRegister'
        ];
    }


    public function onUserRegister(UserRegisterEvent $event)
    {
        $body = $this->twig->render('email/registration.html.twig', [
            'user' => $event->getRegisteredUser()
        ]);

        $message = (new \Swift_Message())
            ->setSubject('Welcome to the micro-post app!')
            ->setFrom('micropost@micropost.com')
            ->setTo($event->getRegisteredUser()->getEmail())
            ->setBody($body,'text/html');

        $this->mailer->send($message);
    }

    /*
     *  To use a event Subscriber, we have to create a event (which symfony autoWired and autoConfigure) and override the NAME of event to dispatch him later in controller
     *
     */

}