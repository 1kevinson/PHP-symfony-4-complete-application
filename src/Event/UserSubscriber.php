<?php

namespace App\Event;


use App\Mailer\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment as Twig_Environment;

class UserSubscriber implements EventSubscriberInterface
{

    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
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
        $this->mailer->sendConfirmationEmail($event->getRegisteredUser());
    }

    /*
     *  To use a event Subscriber, we have to create a event (which symfony autoWired and autoConfigure) and override the NAME of event to dispatch him later in controller
     *
     */

}