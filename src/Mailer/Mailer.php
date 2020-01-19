<?php


namespace App\Mailer;


use App\Entity\User;
use Twig\Environment as Twig_Environment;

class Mailer
{
    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendConfirmationEmail(User $user)
    {
        $body = $this->twig->render('email/registration.html.twig', [
            'user' => $user
        ]);

        $message = (new \Swift_Message())
            ->setSubject('Welcome to the micro-post app!')
            ->setFrom('micropost@micropost.com')
            ->setTo($user->getEmail())
            ->setBody($body,'text/html');

        $this->mailer->send($message);
    }



}