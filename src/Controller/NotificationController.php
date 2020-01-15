<?php


namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granded("ROLE_USER"))
 * @Route("/notification")
 */
class NotificationController extends AbstractController
{
    /**
     * @Route("/unread-count", name="notification_unread")
     */
    public function unreadCount()
    {

    }
}