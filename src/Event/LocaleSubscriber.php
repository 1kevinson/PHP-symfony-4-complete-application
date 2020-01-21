<?php


namespace App\Event;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{

    /**
     * @var string
     */
    private $defaultLocale;

    public function __construct($defaultLocale = 'en')
    {

        $this->defaultLocale = $defaultLocale;
    }

    /*
    * Use to set locale translation with
    *
    * */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                [
                    'onKernelRequest',
                    20
                ],
            ],
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if(!$request->hasPreviousSession())
        {
            return;
        }

        // $this->defaultLocale (constructer parameter) is override by ['%kernel.default_locale%']  #configure inside the translation.yml file
        if($locale = $request->attributes->get('_locale'))
        {
            $request->getSession()->set('_locale',$locale);
        }else {
            $request->setLocale(
                $request->getSession()->get('_locale', $this->defaultLocale[0])
            );
        }
    }

    /*  20 represent priority execution because we play the UserLocaleSubscriber first  */
}