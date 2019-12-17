<?php


namespace App\Service;


use Doctrine\Common\Annotations\Annotation\Required;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class VeryBadDesign implements ContainerAwareInterface
{

    /**
     * @Required()
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $container->get('app.greeting' );
    }
}